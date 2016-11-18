<?php

namespace Portal\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use Portal\Application;
use Portal\Http\Requests\ApplicationCreateRequest;
use Portal\Http\Requests\ApplicationStepFiveRequest;
use Portal\Http\Requests\ApplicationStepFourRequest;
use Portal\Http\Requests\ApplicationStepOneRequest;
use Portal\Http\Requests\ApplicationStepThreeRequest;
use Portal\Http\Requests\ApplicationStepTwoRequest;
use Portal\User;
use Response;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('application-form.form');
    }

    /**
     * After validating the input we create a new user account
     * and create the application form against this user.
     *
     * @param Request|ApplicationCreateRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|User
     */
    public function store(ApplicationCreateRequest $request)
    {

        // Validation handled in Request

        try {

            // Create a new user account and log them in
            User::create([
                'first_name' => $request['first_name'],
                'last_name'  => $request['last_name'],
                'email'      => $request['email'],
                'password'   => bcrypt($request['password']),
                'role'       => 'tenant'
            ]);

            if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {

                // Create a new application form
                Application::create([
                    'user_id' => Auth::user()->id,
                    'status'  => 'draft',
                    'step'    => '1'
                ]);

                $applicationForm = Application::whereUserId(Auth::user()->id)
                    ->whereStatus('draft')
                    ->whereStep(1)
                    ->first();

                // Redirect to the edit form page

                return redirect()->action(
                    'ApplicationController@edit', ['id' => $applicationForm->id]
                );

            } else {
                \Log::info('error authing user');
            }

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            return Response::json([
                'error'   => 'application_form_step1_error',
                'message' => trans('portal.application_form_step1_error'),
            ], 422);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // abort unless logged in

        // Find the application based on the ID passed

        // return the view to the user

    }

    /**
     * Show the form for editing the application form.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        // Find the application based on the ID
        $applicationForm = Application::find($id);

        // abort unless logged in and the ID must belong to the user
        abort_unless(Auth::check() && $applicationForm->user_id == Auth::user()->id, 401);

        // return the view to the user
        return view('application-form.edit', compact('applicationForm'));

    }

    /**
     * Update the application form for Step One.
     *
     * @param Request|ApplicationStepOneRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepOne(ApplicationStepOneRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            // Update the form
            $applicationForm->update($request->all());

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'application_form_step1_error',
                'message' => trans('portal.application_form_step1_error'),
            ], 422);

        }


    }

    /**
     * Update the application form for Step Two.
     *
     * @param Request|ApplicationStepTwoRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepTwo(ApplicationStepTwoRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            // Update the form against this user

            $applicationForm->update($request->all());

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'application_form_step2_error',
                'message' => trans('portal.application_form_step2_error'),
            ], 422);

        }

    }

    /**
     * Update the application form for Step Three.
     *
     * @param Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepThree(ApplicationStepThreeRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            // Update the form
            $applicationForm->update($request->all());

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'application_form_step3_error',
                'message' => trans('portal.application_form_step3_error'),
            ], 422);

        }


    }

    /**
     * Update the application form for Step Four.
     *
     * @param Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepFour(ApplicationStepFourRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            // Update the form
            $applicationForm->update($request->all());

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'application_form_step4_error',
                'message' => trans('portal.application_form_step4_error'),
            ], 422);

        }


    }

    /**
     * Update the application form for Step Five.
     *
     * @param Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepFive(ApplicationStepFiveRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            // Update the form
            $applicationForm->update($request->all());

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'application_form_step5_error',
                'message' => trans('portal.application_form_step5_error'),
            ], 422);

        }


    }

    /**
     * Update the application form for Step Six.
     *
     * @param Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepSix(Request $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            // Update the form
            $applicationForm->update($request->all());

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'application_form_step6_error',
                'message' => trans('portal.application_form_step6_error'),
            ], 422);

        }


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

        // abort unless logged in user owns this application form
        // It must also not be submission

    }
}
