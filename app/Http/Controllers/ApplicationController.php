<?php

namespace Portal\Http\Controllers;

use DB;
use Illuminate\Http\Request;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Validation handled in Request

        DB::beginTransaction();

        try {

            // Create a new user account and log them in

            // Create a new application form

            // Redirect to the edit form page

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
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        // abort unless logged in and the ID must belong to the user

        // Find the application based on the ID

        // return the view to the user

    }

    /**
     * Update the application form for Step Two.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepTwo(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            // Update the form against this user

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepThree(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            // Update the form against this user

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepFour(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            // Update the form against this user

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepFive(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            // Update the form against this user

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepSix(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            // Update the form against this user

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // abort unless logged in user owns this application form
        // It must also not be submission

    }
}
