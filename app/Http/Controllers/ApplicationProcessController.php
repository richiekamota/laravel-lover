<?php

namespace Portal\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use Portal\Application;
use Portal\ApplicationEvent;
use Portal\Http\Requests\ApplicationApproveRequest;
use Portal\Http\Requests\ApplicationDeclineRequest;
use Portal\Http\Requests\ApplicationPendingRequest;
use Portal\Http\Requests\ApplicationDraftRequest;
use Portal\Http\Requests\ApplicationCancelRequest;
use Portal\Item;
use Portal\Jobs\SendApplicationAcceptedEmail;
use Portal\Jobs\SendApplicationDeclinedEmail;
use Portal\Jobs\SendApplicationPendingEmail;
use Portal\Jobs\SendApplicationChangesRequestedEmail;
use Portal\Jobs\SendApplicationRenewalEmail;
use Portal\Jobs\SendContractCancelledEmail;
use Portal\Location;
use Portal\Contract;
use Portal\Unit;
use Portal\UnitType;
use Portal\OccupationDate;
use Response;

class ApplicationProcessController extends Controller
{

    /**
     * Allow a user to review this application
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function review($id)
    {

        // Find the application based on the ID
        $application = Application::with(['location', 'unitType'])->find($id);
        $contract = Contract::where('application_id','=',$id)->first();
        $application->contract = $contract;

        // return the view to the user
        return view('applications.review', compact('application'));

    }

    /**
     * View the chagnes requested page
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changesRequested($id)
    {

        $this->authorize('review', Application::class);

        $application = Application::with([
            'user',
            'residentId',
            'residentStudyPermit',
            'residentAcceptance',
            'residentFinancialAid',
            'leaseholderId',
            'leaseholderAddressProof',
            'leaseholderPayslip',
        ])->find($id);

        return view('applications.changesRequested', compact('application'));

    }

    /**
     * Mark an applcation back to draft so a user can edit
     */
    public function processChangesRequested(ApplicationDraftRequest $request, $id)
    {
        // Abort unless its policy approves
        $this->authorize('draft', Application::class);

        DB::beginTransaction();

        try {

            $application = Application::findOrFail($id);

            $application->status = 'draft';
            $application->save();

            ApplicationEvent::create([
                'user_id'        => Auth::user()->id,
                'application_id' => $id,
                'action'         => 'Application changes requested',
                'note'           => $request->reason
            ]);

            dispatch(new SendApplicationChangesRequestedEmail($application->user, $application, $request->reason));

            DB::commit();

            return Response::json([
                'message' => trans('portal.process_changes_requested_complete')
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e); // LIVE log of issue

            DB::rollback();

            return Response::json([
                'error'   => 'process_changes_requested_error',
                'message' => trans('portal.process_changes_requested_error').json_encode($e),
            ], 422);

        }
    }

    /**
     * Process the decline of an application and send
     * the applicant an email
     *
     * @param ApplicationDeclineRequest $request
     * @param                           $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function processDecline(ApplicationDeclineRequest $request, $id)
    {

        // Abort unless its policy approves
        $this->authorize('process', Application::class);

        DB::beginTransaction();

        try {

            $application = Application::findOrFail($id);

            $application->status = 'declined';
            $application->save();

            ApplicationEvent::create([
                'user_id'        => Auth::user()->id,
                'application_id' => $id,
                'action'         => 'Application declined',
                'note'           => $request->reason
            ]);

            dispatch(new SendApplicationDeclinedEmail($application->user, $application, $request->reason));

            DB::commit();

            return Response::json([
                'message' => trans('portal.process_decline_complete')
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e); // LIVE log of issue

            DB::rollback();

            return Response::json([
                'error'   => 'process_decline_error',
                'message' => trans('portal.process_decline_error').json_encode($e),
            ], 422);

        }


    }

    /**
     * Process the cancellation of an approved application and send
     * the applicant an email
     *
     * @param ApplicationCancelRequest $request
     * @param                          $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function processCancelApproved(ApplicationCancelRequest $request, $id)
    {

        // Abort unless its policy approves
        $this->authorize('process', Application::class);

        DB::beginTransaction();

        try {

            $application = Application::findOrFail($id);

            $application->status = 'cancelled';
            $application->save();

            $contract = DB::table('contracts')
                ->where('application_id', $application->id)
                ->update(['status' => 'cancelled']);

            $contract = DB::table('contracts')
                ->where('application_id', $application->id)
                ->where('status', '=', 'cancelled')
                ->first();

            $occupiedUnit = DB::table('occupation_dates')
                ->where('application_id', $application->id)
                ->update(['status' => 'cancelled']);

            ApplicationEvent::create([
                'user_id'        => Auth::user()->id,
                'application_id' => $id,
                'action'         => 'Application cancelled',
                'note'           => ''
            ]);

            dispatch(new SendContractCancelledEmail($id, $application->user_id));

            DB::commit();

            return Response::json([
                'message' => trans('portal.process_cancel_complete'),
                'data'    => Application::with(
                    'user',
                    'residentId',
                    'residentStudyPermit',
                    'residentAcceptance',
                    'residentFinancialAid',
                    'leaseholderId',
                    'leaseholderAddressProof',
                    'leaseholderPayslip',
                    'events'
                )->find($id)->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e); // LIVE log of issue

            DB::rollback();

            return Response::json([
                'error'   => 'process_cancel_error',
                'message' => json_encode($e),
            ], 422);

        }


    }

    /**
     * View the pending page
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pending($id)
    {

        $this->authorize('review', Application::class);

        $application = Application::with([
            'user',
            'residentId',
            'residentStudyPermit',
            'residentAcceptance',
            'residentFinancialAid',
            'leaseholderId',
            'leaseholderAddressProof',
            'leaseholderPayslip',
        ])->find($id);

        // Find out the unit types at this applications location
        $location = Location::find($application->unit_location);

        $unitTypes = DB::table('units')
            ->where('location_id', $application->unit_location)
            ->where('user_id', NULL)
            ->select('type_id', DB::raw('count(*) as total'))
            ->groupBy('type_id')
            ->get();

        foreach ($unitTypes as $unitType) {
            $unitType->name = UnitType::find($unitType->type_id)->name;
        }

        return view('applications.pending', compact('application', 'location', 'unitTypes'));

    }

    /**
     * Handle the process of marking an application
     * as pending and email the applicant
     *
     * @param ApplicationPendingRequest $request
     * @param                           $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPending(ApplicationPendingRequest $request, $id)
    {

        // Abort unless its policy approves
        $this->authorize('process', Application::class);

        DB::beginTransaction();

        try {

            $application = Application::findOrFail($id);

            $application->status = 'pending';
            $application->save();

            ApplicationEvent::create([
                'user_id'        => Auth::user()->id,
                'application_id' => $id,
                'action'         => 'Application pending',
                'note'           => $request->reason
            ]);

            dispatch(new SendApplicationPendingEmail($application->user, $application, $request->reason));

            DB::commit();

            return Response::json([
                'message' => trans('portal.process_decline_complete'),
                'data'    => Application::with(
                    'user',
                    'residentId',
                    'residentStudyPermit',
                    'residentAcceptance',
                    'residentFinancialAid',
                    'leaseholderId',
                    'leaseholderAddressProof',
                    'leaseholderPayslip',
                    'events'
                )->find($id)->toArray()
            ], 200);


        } catch (\Exception $e) {

            \Log::info($e); // LIVE log of issue

            DB::rollback();

            return Response::json([
                'error'   => 'process_pending_error',
                'message' => trans('portal.process_pending_error').json_encode($e),
            ], 422);


        }

    }

    /**
     * View the approve page
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function approve($id)
    {

        $this->authorize('review', Application::class);

        $application = Application::with([
            'user',
            'residentId',
            'residentStudyPermit',
            'residentAcceptance',
            'residentFinancialAid',
            'leaseholderId',
            'leaseholderAddressProof',
            'leaseholderPayslip',
            'location',
            'unitType'
        ])->find($id);

        // Find out the unit types at this applications location
        $location = Location::find($application->unit_location);

        // TODO Attached the items for this application to assist with
        // the contract generation
        $suggestedItems = UnitType::find($application->unit_type)->items;

        $items = Item::all();

        $availableUnitsArr = Unit::where('user_id', '=', '')
            ->orWhere('user_id', '=', NULL)
            ->where('type_id', $application->unit_type)
            ->get();

        $availableUnits = array();
        foreach ($availableUnitsArr as $u) {
            $occupationDates = OccupationDate::where("unit_id", "=", $u->id)->where("status", "<>", "cancelled")->get();
            $u->occupation_dates = $occupationDates->toArray();
            $availableUnits[] = $u;
        }

        $availableUnits = json_encode($availableUnits);

        return view('applications.approve', compact('application', 'location', 'suggestedItems', 'items', 'availableUnits'));

    }

    /**
     * Renew an existing application
     *
     * @param Request|ApplicationCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|User
     */
    public function renew($id)
    {

        // Validation handled in Request
        try {

            // Create new application based on existing approved application
            $currentApplication = Application::find($id);
            $currentApplicationArray = $currentApplication->toArray();
            $currentApplicationArray['status'] = 'draft';

            // Create a new application form
            Application::create($currentApplicationArray);
            $application = Application::where('user_id', $currentApplicationArray['user_id'])
                ->whereStatus('draft')
                ->first();

            // Generate the secure return email for this contract
            dispatch(new SendApplicationRenewalEmail($application->id, $currentApplicationArray['user_id']));

        } catch (\Exception $e) {

            \Log::info($e); // LIVE log of issue

            return Response::json([
                'error'   => 'application_form_step1_error',
                'message' => json_encode($e),
            ], 422);

        }

    }
}
