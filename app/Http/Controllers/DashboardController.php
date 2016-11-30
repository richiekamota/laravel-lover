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

            $openApplications = Application::whereStatus('open')->get();

            $pendingApplications = Application::whereStatus('pending')->get();

            $allApplications = Application::whereStatus('declined')->orWhere('status', '!=', 'approved')->get();

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
