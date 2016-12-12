<?php

namespace Portal\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use Portal\Application;
use Portal\ApplicationEvent;
use Portal\Http\Requests\ApplicationDeclineRequest;
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

    public function processDecline(ApplicationDeclineRequest $request, $id )
    {

        // Abort unless its policy approves
        $this->authorize('process', Application::class);

        DB::beginTransaction();

        try {

            // Find the application by ID
            Application::where('id', $id)
                ->update([
                    'status' => 'declined'
                ]);

            ApplicationEvent::create([
                'user_id' => Auth::user()->id,
                'application_id' => $id,
                'action' => 'Application declined',
                'note' => $request->reason
            ]);

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

}
