<?php

namespace Portal\Http\Controllers;

use Gate;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * Show a list of all the users in the site
     */
    public function index()
    {

        abort_unless(Gate::allows('is-admin'), 401);

        return view('users.index');

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        return view('users.profile');
    }

}
