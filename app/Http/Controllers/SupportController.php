<?php

namespace Portal\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Portal\Http\Requests\SupportAdminErrorRequest;
use Portal\Jobs\SendGitLabTicketEmail;
use Response;

class SupportController extends Controller
{
    /**
     * Handle the incoming request and send an email to GitLab for action.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIssue( SupportAdminErrorRequest $request )
    {

        // Try to send an email to GitLab email address
        try {

            dispatch(new SendGitLabTicketEmail(Auth::user(), $request->user_issue, $request->location));
            
            return Response::json([
                'message' => trans('portal.support_admin_issue_complete')
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e); // LIVE: Match back to Sentry for issue tracking

            return Response::json([
                'error'   => 'support_admin_error',
                'message' => trans('portal.support_admin_error'),
            ], 422);

        }

    }
}
