<?php

namespace Portal\Http\Controllers;

use Auth;
use Gate;
use Illuminate\Http\Request;
use Portal\Application;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // If the user is not a tenant
        if (Gate::denies('is-tenant')) {

            $openApplications = Application::with('user', 'location')->whereStatus('open')->get();

            $pendingApplications = Application::with('user', 'location')->whereStatus('pending')->get();

            $allApplications = Application::with('user', 'location')
                ->where('status', '=', 'declined')
                ->orWhere('status', '=', 'approved')
                ->get();

            return view('dashboard', compact('openApplications', 'pendingApplications', 'allApplications'));

        } else {

            $openApplications = Auth::user()->applications;

            return view('dashboard', compact('openApplications'));

        }

    }

    /**
     * Return the UI Kit page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uiKit()
    {

        return view('ui-kit');

    }
}
