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
use Portal\Item;
use Portal\Jobs\SendApplicationAcceptedEmail;
use Portal\Jobs\SendApplicationDeclinedEmail;
use Portal\Jobs\SendApplicationPendingEmail;
use Portal\Location;
use Portal\Unit;
use Portal\UnitType;
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
    public function review( $id )
    {

        $this->authorize( 'review', Application::class );

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

        return view('applications.review', compact('application'));

    }

    /**
     * Process the decline of an application and send
     * the applicant an email
     *
     * @param ApplicationDeclineRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function processDecline(ApplicationDeclineRequest $request, $id )
    {

        // Abort unless its policy approves
        $this->authorize('process', Application::class);

        DB::beginTransaction();

        try {

            $application = Application::findOrFail($id);

            $application->status = 'declined';
            $application->save();

            ApplicationEvent::create([
                'user_id' => Auth::user()->id,
                'application_id' => $id,
                'action' => 'Application declined',
                'note' => $request->reason
            ]);

            dispatch(new SendApplicationDeclinedEmail($application->user, $application, $request->reason));

            DB::commit();

            return Response::json([
                'message' => trans('portal.process_decline_complete'),
                'data' => Application::with(
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

            \Log::info( $e );

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json( [
                'error'   => 'process_decline_error',
                'message' => trans( 'portal.process_decline_error' ),
            ], 422 );

        }

        // Update the application status

        // Email the user with the reason

        // Return the updated application to the user


    }

    /**
     * View the pending page
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pending( $id )
    {

        $this->authorize( 'review', Application::class );

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

        foreach( $unitTypes as $unitType){
            $unitType->name = UnitType::find($unitType->type_id)->name;
        }

        return view('applications.pending', compact('application', 'location', 'unitTypes'));

    }

    /**
     * Handle the process of marking an application
     * as pending and email the applicant
     *
     * @param ApplicationPendingRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPending(ApplicationPendingRequest $request, $id )
    {

        // Abort unless its policy approves
        $this->authorize('process', Application::class);

        DB::beginTransaction();

        try {

            $application = Application::findOrFail($id);

            $application->status = 'pending';
            $application->save();

            ApplicationEvent::create([
                'user_id' => Auth::user()->id,
                'application_id' => $id,
                'action' => 'Application pending',
                'note' => $request->reason
            ]);

            dispatch(new SendApplicationPendingEmail($application->user, $application, $request->reason));

            DB::commit();

            return Response::json([
                'message' => trans('portal.process_decline_complete'),
                'data' => Application::with(
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

            \Log::info( $e );

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json( [
                'error'   => 'process_pending_error',
                'message' => trans( 'portal.process_pending_error' ),
            ], 422 );


        }

    }

    /**
     * View the approve page
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function approve( $id )
    {

        $this->authorize( 'review', Application::class );

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

        $availableUnits = Unit::where('user_id', '=', '')
            ->orWhere('user_id', '=', null)
            ->where('type_id', $application->unit_type)
            ->get();

        return view('applications.approve', compact('application', 'location', 'suggestedItems', 'items', 'availableUnits'));

    }

}
