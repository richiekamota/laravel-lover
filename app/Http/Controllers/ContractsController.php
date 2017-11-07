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
use Portal\Jobs\ConvertNumberToText;

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

        DB::beginTransaction();

        try {

            $oldContract = Contract::where('application_id', $id)
                                    ->where('status', '<>', 'cancelled')                        
                                    ->first();
            // Cancell the existing applications if there are any
            if(!empty($oldContract)){
                $oldContract->status = 'cancelled';
                $oldContract->save();

                // Log an event against the application
                ApplicationEvent::create([
                    'user_id'        => Auth::user()->id,
                    'application_id' => $id,
                    'action'         => 'Application cancelled',
                    'note'           => 'New incoming application'
                ]);

                OccupationDate::where('application_id', $id)
                                ->update(['status' => 'cancelled']);

            }

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
                    'message' => "The selected unit is not available for the occupation date period specified." . json_encode($occupiedUnit->toArray(), TRUE)
                ], 422);
            }

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
                        'contract_id'  => $contract->id,
                        'name'         => $item['name'],
                        'description'  => $item['description'],
                        'value'        => $item['cost'],
                        'payment_type' => $item['payment_type'],
                    ]);

                }
            }

            // Generate a PDF of the contract based on the application
            // The name should be the user first and last name and the date
            // eg FirstName20160424.pdf
            $pdfName = ucfirst(preg_replace('/[^\w-]/', '', $applicationUser->first_name)) . ucfirst(preg_replace('/[^\w-]/', '', $applicationUser->last_name)) . \Carbon\Carbon::today()->toDateString();

            $contract_data = Contract::where('id', '=', $contract->id)->with('items', 'user')->first();
            $contract_data->application = Application::findOrFail($contract_data->application_id);
            $contract_data->unit = Unit::findOrFail($contract_data->unit_id);
            $contract_data->location = Location::findOrFail($contract_data->unit->location_id);
            $contract_data->occupation_date = OccupationDate::where('application_id', '=', $contract_data->application_id)->first();
            $contract_data->start_date = Carbon::parse($contract_data->start_date)->format("d F Y");
            $contract_data->end_date = Carbon::parse($contract_data->end_date)->format("d F Y");
            $contract_data->onceoff_total = 0;
            $contract_data->monthly_total = 0;
            $contract_data->deposit_total = 0;
            $contract_data->rental_total = 0;
            $contract_data->deposit_text = '';
            $contract_data->rental_text = '';

            foreach ($contract_data->items as $item) {
                if ($item->payment_type == 'Monthly') {
                    $contract_data->monthly_total += $item->value;
                } else {
                    $contract_data->onceoff_total += $item->value;
                }

                if($item->payment_type == 'Deposit'){
                    $contract_data->deposit_total += $item->value;
                }

                if($item->payment_type == 'Rental'){
                    $contract_data->rental_total += $item->value;
                }

                $item->value = number_format($item->value,2,".",",");
            }

            $contract_data->onceoff_total = number_format($contract_data->onceoff_total,2,".",",");
            $contract_data->monthly_total = number_format($contract_data->monthly_total,2,".",",");

            $contract_data->deposit_text = new ConvertNumberToText($contract_data->deposit_total,0,".","");
            $contract_data->deposit_text = $contract_data->deposit_text->toWords(number_format($contract_data->deposit_total,0,".",""));

            $contract_data->rental_text = new ConvertNumberToText($contract_data->rental_total);
            $contract_data->rental_text = $contract_data->rental_text->toWords(number_format($contract_data->rental_total,0,".",""));

            $contract_data->deposit_total = number_format($contract_data->deposit_total,2,".",",");

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
            $contract->items = $contract->items->sortByDesc('cost');
            $contract->application = Application::findOrFail($contract->application_id);
            $contract->unit = Unit::findOrFail($contract->unit_id);
            $contract->location = Location::findOrFail($contract->unit->location_id);
            $contract->occupation_date = OccupationDate::where('application_id', '=', $contract->application_id)->first();
            $contract->start_date = Carbon::parse($contract->start_date)->format("d F Y");
            $contract->end_date = Carbon::parse($contract->end_date)->format("d F Y");

            $contract->onceoff_total = 0;
            $contract->monthly_total = 0;
            $contract->deposit_total = 0;
            $contract->rental_total = 0;
            $contract->deposit_text = '';
            $contract->rental_text = '';

            foreach ($contract->items as $item) {
                if ($item->payment_type == 'Monthly') {
                    $contract->monthly_total += $item->value;
                } else {
                    $contract->onceoff_total += $item->value;
                }

                if($item->payment_type == 'Deposit'){
                    $contract->deposit_total += $item->value;
                }

                if($item->payment_type == 'Rental'){
                    $contract->rental_total += $item->value;
                }

                $item->value = number_format($item->value,2,".",",");
            }

            $contract->onceoff_total = number_format($contract->onceoff_total,2,".",",");
            $contract->monthly_total = number_format($contract->monthly_total,2,".",",");

            $contract->deposit_text = new ConvertNumberToText(number_format($contract->deposit_total,0,"",""));
            $contract->deposit_text = $contract->deposit_text->toWords(number_format($contract->deposit_total,0,".",""));

            $contract->rental_text = new ConvertNumberToText($contract->rental_total);
            $contract->rental_text = $contract->rental_text->toWords(number_format($contract->rental_total,0,".",""));

            $contract->deposit_total = number_format($contract->deposit_total,2,".",",");


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

        DB::beginTransaction();

        try {

            $contract = Contract::findOrFail($id);
            $application = Application::findOrFail($contract->application_id);

            // find the contract
            DB::table('contracts')
            ->where('id', $contract->id)
            ->update(['status' => 'approved']);

            DB::table('applications')
            ->where('id', $contract->application_id)
            ->update(['status' => 'approved']);

            DB::table('occupation_dates')
            ->where('contract_id', $contract->id)
            ->update(['status' => 'approved']);

            // TODO do we need to update the contract on the file system
            // to show that a user has approved it.

            // Send an email to the accounting team so they can update the user
            dispatch(new SendApprovedContractToAccounts(Auth::user(), $contract, $application));

            DB::commit();

            return Response::json([
                'message' => trans('portal.contracts_approve_complete')
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

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
