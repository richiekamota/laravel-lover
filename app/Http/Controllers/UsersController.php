<?php

namespace Portal\Http\Controllers;

use Gate;
use DB;
use Portal\User;
use Illuminate\Http\Request;
use Portal\Http\Requests\UserEditRequest;
use Response;

class UsersController extends Controller
{

    /**
     * Show a list of all the users in the site
     */
    public function index()
    {

        abort_unless(Gate::allows('is-admin'), 401);
        $users = User::where("role","=","tenant")->get();

        return view('users.index', compact('users'));

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        return view('users.profile');
    }

    public function update( UserEditRequest $request, $id )
    {

        // abort unless Auth > tenant
        abort_unless(Gate::allows('is-admin'), 401);

        DB::beginTransaction();

        try {

            User::findOrFail($id)->update(['tenant_code' => $request->tenant_code]);

            DB::commit();

            return Response::json([
                'message' => trans('portal.user_edit_complete'),
                'data' => User::findOrFail($id)->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'user_edit_error',
                'message' => trans('portal.user_edit_error'),
            ], 422);

        }

    }
}
