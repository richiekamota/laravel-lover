<?php

namespace Portal\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Encryption\DecryptException;
use PDF;
use Illuminate\Http\Request;
use Portal\Application;
use Portal\ApplicationEvent;
use Portal\Contract;
use Portal\ContractItem;
use Portal\Document;
use Portal\OccupationDate;
use Portal\Http\Requests\ContractCreateRequest;
use Portal\Http\Requests\ContractApproveRequest;
use Portal\Jobs\SendApprovedContractToAccounts;
use Portal\Jobs\SendContractToUserEmail;
use Portal\Unit;
use Portal\User;
use Portal\Location;
use Response;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Show a list of all the contracts
        // return view('contracts.index');

    }

    /**
     * Return a JSON listing of the resource
     * that belongs to this users based on passed in ID
     * of the current Authed users.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForUser()
    {

        // Find all the contracts for the Auth user

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|ContractCreateRequest $request
     * @param null                          $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(ContractCreateRequest $request, $id = NULL)
    {

        // about unless Auth is level above tenant
        $this->authorize('create', Contract::class);
        $filePath = FALSE;

        // Check if selected unit available for occupation date period
        $occupiedUnit = OccupationDate::where('unit_id', '=', $request->unit_id)
            ->where('start_date', '>=', Carbon::parse($request->unit_occupation_date)->format("Y-m-d H:i:s"))
            ->where('start_date', '<=', Carbon::parse($request->unit_vacation_date)->format("Y-m-d H:i:s"))
            ->where('end_date', '>=', Carbon::parse($request->unit_occupation_date)->format("Y-m-d H:i:s"))
            ->where('end_date', '<=', Carbon::parse($request->unit_vacation_date)->format("Y-m-d H:i:s"))
            ->where('status', '<>', 'cancelled')
            ->get();

        if (!empty($occupiedUnit->toArray())) {
            return Response::json([
                'error'   => '',
                'message' => "The selected unit is not available for the occupation date period specified." . json_encode($occupiedUnit->toArray(), true)
            ], 422);
        }

        DB::beginTransaction();

        try {

            $application = Application::findOrFail($id);

            // Approve the application
            $application->status = 'approved';
            $application->save();

            $applicationUser = User::findOrFail($application->user_id);

            // Take the request and store in the DB
            $contract = Contract::create([
                'user_id'        => $applicationUser->id,
                'unit_id'        => $request->unit_id,
                'start_date'     => Carbon::parse($request->unit_occupation_date),
                'end_date'       => Carbon::parse($request->unit_vacation_date),
                'application_id' => $id,
                'status'         => 'pending'
            ]);

            // Create entry in occupation dates table
            $occupationDate = OccupationDate::create([
                'contract_id'    => $contract->id,
                'application_id' => $id,
                'unit_id'        => $request->unit_id,
                'status'         => 'pending',
                'start_date'     => Carbon::parse($request->unit_occupation_date),
                'end_date'       => Carbon::parse($request->unit_vacation_date)
            ]);

            // Save the items array into the contract so we
            // have a structured list of the items for this
            // specific contract
            if ($request->items) {
                foreach ($request->items as $item) {

                    // Create the contract item
                    ContractItem::create([
                        'contract_id'     => $contract->id,
                        'name'            => $item['name'],
                        'description'     => $item['description'],
                        'value'           => $item['cost'],
                        'monthly_payment' => ($item['monthly_payment']?$item['monthly_payment']:0),
                    ]);

                }
            }

            // Generate a PDF of the contract based on the application
            // The name should be the user first and last name and the date
            // eg FirstName20160424.pdf
            $pdfName = ucfirst(preg_replace('/[^\w-]/', '', $applicationUser->first_name)) . ucfirst(preg_replace('/[^\w-]/', '', $applicationUser->last_name)) . \Carbon\Carbon::today()->toDateString();

            $contract_data = Contract::where('id','=',$contract->id)->with('items', 'user')->first();
            $contract_data->application = Application::findOrFail($contract_data->application_id);
            $contract_data->unit = Unit::findOrFail($contract_data->unit_id);
            $contract_data->location = Location::findOrFail($contract_data->unit->location_id);
            $contract_data->occupation_date = OccupationDate::where('application_id', '=', $contract_data->application_id)->first();

            $data = ['name' => $applicationUser->first_name, 'contract' => $contract_data];
            $filePath = storage_path('contracts/' . $pdfName . '.pdf');

            // Delete existing PDF
            if (file_exists(storage_path('contracts/' . $pdfName . '.pdf'))) {
                unlink(storage_path('contracts/' . $pdfName . '.pdf'));
            }

            $pdf = PDF::loadView('pdf.contract', $data)->save($filePath);

            $document = Document::create([
                'user_id'   => $applicationUser->id,
                'location'  => $pdfName . '.pdf',
                'type'      => 'contract',
                'file_name' => $pdfName . '.pdf'
            ]);

            // TODO Save the PDF into S3

            // Generate a secure link for the user_id passed in
            $contract->secure_link = encrypt($applicationUser->email . '##' . $filePath);
            // Attach the document to the contract record
            $contract->document_id = $document->id;
            $contract->save();

            // Log an event against the application
            ApplicationEvent::create([
                'user_id'        => Auth::user()->id,
                'application_id' => $application->id,
                'action'         => 'Application approved',
                'note'           => ''
            ]);

            // Generate the secure return email for this contract
            dispatch(new SendContractToUserEmail($filePath, $applicationUser->id, $contract->secure_link, $application->id));

            DB::commit();

            return Response::json([
                'message' => trans('portal.contracts_store_complete'),
                'data'    => $contract->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'contracts_store_error',
                'message' => json_encode($e),
            ], 422);

        }

    }

    /**
     * Show a user a copy of the contract for approval.
     *
     * @param $secureLink
     *
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show($secureLink)
    {

        // Decrypt the link
        try {
            $decrypted = decrypt($secureLink);
            $secureArray = explode("##", $decrypted);

            abort_unless($secureArray[0] == Auth::user()->email, 401);

            // Find the secureLink in the DB
            $contract = Contract::whereSecureLink($secureLink)->with('items', 'user')->first();
            $contract->application = Application::findOrFail($contract->application_id);
            $contract->unit = Unit::findOrFail($contract->unit_id);
            $contract->location = Location::findOrFail($contract->unit->location_id);
            $contract->occupation_date = OccupationDate::where('application_id', '=', $contract->application_id)->first();

            // print_r($contract->toArray());

            $contract->onceoff_total = 0;
            $contract->monthly_total = 0;

            foreach($contract->items as $item){
                if($item->monthly_payment == 1){
                    $contract->monthly_total += $item->value;
                }else{
                    $contract->onceoff_total += $item->value;
                }
            }

            // Check the secure link user_id matches that of the Auth::user()
            abort_unless($contract->user_id == Auth::user()->id, 401);

            // Return the use the view with their contract details
            return view('contracts.review', compact('contract'));

        } catch (DecryptException $e) {
            abort(500);
        }

    }

    /**
     * @param ContractApproveRequest $request
     * @param                        $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(ContractApproveRequest $request, $id)
    {

        // If the logged in user is matching the ID
        abort_unless($request->user_id == Auth::user()->id, 422);

        // find the contract
        $contract = Contract::with('user', 'unit', 'items', 'application')->find($id);

        abort_unless($contract->isEmpty(), 422);

        DB::beginTransaction();

        try {

            $contract->approved = Carbon::now();
            $contract->save();

            $occupationDate = OccupationDate::where('contract_id', '=', $contract->idphpphp);

            $occupationDate->status = 'approved';
            $occupationDate->save();

            // TODO do we need to update the contract on the file system
            // to show that a user has approved it.

            // Send an email to the accounting team so they can update the user
            dispatch(new SendApprovedContractToAccounts(Auth::user(), $contract));

            DB::commit();

            return Response::json([
                'message' => trans('portal.contracts_approve_complete')
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'contracts_approve_error',
                'message' => trans('portal.contracts_approve_error'),
            ], 422);

        }


    }

    public function download($id)
    {

        $contract = Contract::findOrFail($id);

        // If the logged in user is matching the ID
        abort_unless($contract->user->id == Auth::user()->id, 422);

        return response()->download($contract->document->location);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // abort unless Auth > tenant

        // soft delete the record

    }
}
