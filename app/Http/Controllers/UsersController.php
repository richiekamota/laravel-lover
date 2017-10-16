<?php

namespace Portal\Http\Controllers;

use Gate;
use DB;
use Portal\User;
use Portal\Contract;
use Portal\Unit;
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

        $usersArr = User::where("role","=","tenant")->get();
        $users = array();
        foreach ($usersArr->toArray() as $u) {

            $contracts = Contract::where("user_id", "=", $u['id'])->get();
            $u['contracts'] = array();

            foreach($contracts->toArray() as $c){

                $unit = Unit::where("id", "=", $c['unit_id'])->get()->toArray();

                $c['unit_code'] = $unit[0]['code'];
                $u['contracts'][] = $c;

            }
            $users[] = $u;
        }

        $users = json_encode($users);

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
