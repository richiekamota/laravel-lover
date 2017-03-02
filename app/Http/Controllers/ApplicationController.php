<?php

namespace Portal\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Portal\Application;
use Portal\Http\Requests\ApplicationCreateRequest;
use Portal\Http\Requests\ApplicationStepEightRequest;
use Portal\Http\Requests\ApplicationStepFiveRequest;
use Portal\Http\Requests\ApplicationStepFourRequest;
use Portal\Http\Requests\ApplicationStepOneRequest;
use Portal\Http\Requests\ApplicationStepSevenRequest;
use Portal\Http\Requests\ApplicationStepSixRequest;
use Portal\Http\Requests\ApplicationStepThreeRequest;
use Portal\Http\Requests\ApplicationStepTwoRequest;
use Portal\Http\Requests\ApplicationSubmitRequest;
use Portal\Location;
use Portal\UnitType;
use Portal\User;
use Portal\Unit;
use Portal\Contract;
use Portal\OccupationDate;
use Response;
use Validator;

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
     *
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
                    'current_property_owner' => false,
                    'selfemployed' => false,
                    'judgements' => false,
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
     * New application for existing user
     *
     * @param Request|ApplicationCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|User
     */
    public function store_new()
    {
        // Validation handled in Request

        $applications = Application::with('user', 'location')
            ->where('user_id', '=', Auth::user()->id)
            ->where('status', '=', 'draft')
            ->get();

        if (!empty($applications->toArray())) {
            return Response::json([
                'error'   => 'application_form_new_error',
                'message' => trans('portal.application_form_new_error'),
            ], 422);
        }

        try {

            if (Auth::check()) {

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
        // TODO update this to a policy method
        abort_unless(Auth::check() && $applicationForm->user_id == Auth::user()->id, 401);

        $unitTypes = UnitType::all();
        $locations = Location::all();

        // return the view to the user
        return view('application-form.edit', compact('applicationForm', 'unitTypes', 'locations'));

    }

    /**
     * Show the page for reviewing the application form.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function review($id)
    {

        // Find the application based on the ID
        $applicationForm = Application::find($id);

        // abort unless logged in and the ID must belong to the user
        // TODO update this to a policy method
        abort_unless(Auth::check() && $applicationForm->user_id == Auth::user()->id, 401);

        $unitTypes = UnitType::all();
        $locations = Location::all();

        // return the view to the user
        return view('application-form.review', compact('applicationForm', 'unitTypes', 'locations'));

    }

    /**
     * Update the application form for Step One.
     *
     * @param Request|ApplicationStepOneRequest $request
     * @param  int                              $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepOne(ApplicationStepOneRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            $data = $request->all();
            $data['step'] = 2;

            // Update the form
            $output = $applicationForm->update($data);

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'application_form_step1_error',
                'message' => $e,
            ], 422);

        }


    }

    /**
     * Update the application form for Step Two.
     *
     * @param Request|ApplicationStepTwoRequest $request
     * @param  int                              $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepTwo(ApplicationStepTwoRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            $data = $request->all();
            $data['step'] = 3;

            // Update the form
            $applicationForm->update($data);

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
     * @param  int    $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepThree(ApplicationStepThreeRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            $data = $request->all();
            $data['step'] = 4;

            // Update the form
            $applicationForm->update($data);

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
     * @param  int    $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepFour(ApplicationStepFourRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            $data = $request->all();
            $data['step'] = 5;

            // Update the form
            $applicationForm->update($data);

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'application_form_step4_error',
                'message' => trans('portal.application_form_step4_error') . "<!-- " . $e . " -->",
            ], 422);

        }


    }

    /**
     * Update the application form for Step Five.
     *
     * @param Request $request
     * @param  int    $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepFive(ApplicationStepFiveRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            $data = $request->all();
            $data['step'] = 6;

            // Update the form
            $applicationForm->update($data);

            // Check if selected unit type is available for occupation date specified
            $found = 0;
            $units = Unit::where('type_id','=',$request->unit_type)->get();
            $unit_vacation_date = Carbon::parse(date("Y-m-d", strtotime($request->unit_occupation_date . "+9 months")))->format("Y-m-d H:i:s");

            foreach($units as $u) {
                $occupiedUnit = OccupationDate::where('unit_id', '=', $u->id)
                    ->where('start_date', '>=', Carbon::parse($request->unit_occupation_date)->format("Y-m-d H:i:s"))
                    ->where('start_date', '<=', Carbon::parse($unit_vacation_date)->format("Y-m-d H:i:s"))
                    ->where('end_date', '>=', Carbon::parse($request->unit_occupation_date)->format("Y-m-d H:i:s"))
                    ->where('end_date', '<=', Carbon::parse($unit_vacation_date)->format("Y-m-d H:i:s"))
                    ->where('status', '<>', 'cancelled')
                    ->get();

                if (empty($occupiedUnit->toArray())) {
                    $found++;
                }
            }

            DB::commit();
            if($found == 0){
                $type = UnitType::where('id','=', $request->unit_type)->first();
                return Response::json([
                    'error'   => 'Unit Not Available',
                    'message' => "Please note, there are no ".$type->name." type units available for the occupation date specified. However, you can still submit this application and we will contact you regarding alternative accomoodation.",
                ], 200);
            }

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
     * @param  int    $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepSix(ApplicationStepSixRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            $data = $request->all();
            $data['step'] = 7;

            // Update the form
            $applicationForm->update($data);


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
     * Update the application form for Step Seven.
     * We don't use the route validation as this is all doc
     * uploads and we check them in the next step
     *
     * @param Request|ApplicationStepSevenRequest $request
     * @param  int                                $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepSeven(ApplicationStepSevenRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        $validator = $this->step7_validator($applicationForm->toArray());

        if ($validator->fails()) {
            $message = $validator->errors();

            return Response::json(
                $message
                , 422);
        }

        try {

            // Validate images


            // Update the form
            $applicationForm->update([
                'step' => 8
            ]);

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
     * Update the application form for Step Eight.
     *
     * @param Request|ApplicationStepEightRequest|ApplicationStepSevenRequest $request
     * @param  int                                                            $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function stepEight(ApplicationStepEightRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {
            // Set timestamp for Application confirmation
            $confirm_timestamp = Carbon::now();
            $applicationArr = $applicationForm->toArray();
            $applicationArr['confirm_time'] = $confirm_timestamp;

            // Validate everything!!!
            $validator = $this->validator($applicationArr);

            if ($validator->fails()) {
                $message = $validator->errors();

                return Response::json(
                    $message
                    , 422);
            }

            // Update the form
            $data = $request->all();
            $data['confirm_time'] = $confirm_timestamp;
            $applicationForm->update($data);

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'application_form_step8_error',
                'message' => trans('portal.application_form_step8_error'),
            ], 422);

        }


    }

    /**
     * Submit application for review
     *
     * @param Request|ApplicationStepEightRequest $request
     * @param  int                                $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function submit(ApplicationSubmitRequest $request, $id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {
            // Validate everything again
            $validator = $this->validator($applicationForm->toArray());

            if ($validator->fails()) {
                $message = $validator->errors();

                return Response::json(
                    $message
                    , 422);
            }


            // Update the form
            $data = $request->all();
            $data['status'] = 'open';

            // Unset image keys, as theese are empty and will overwrite what's already in the db
            unset($data['resident_id']);
            unset($data['resident_study_permit']);
            unset($data['resident_acceptance']);
            unset($data['resident_financial_aid']);
            unset($data['leaseholder_id']);
            unset($data['leaseholder_address_proof']);
            unset($data['leaseholder_payslip']);

            $applicationForm->update($data);

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'application_form_step8_error',
                'message' => $e,
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

    /**
     * Cancel the application form and delete
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {

        $applicationForm = Application::find($id);

        $this->authorize('update', $applicationForm);

        DB::beginTransaction();

        try {

            // Update the form status to cancelled
            $data = $applicationForm->toArray();
            $data['status'] = 'cancelled';
            $applicationForm->update($data);

            $contract = Contract::where("application_id","=",$id)->get();
            if(!empty($contract)) {
                $contractData = $contract->toArray();
                $contractData['status'] = 'cancelled';
                $contract->update($contractData);
            }

            $occupationDate = OccupationDate::where("contract_id","=",$contractData['id'])->get();
            if(!empty($occupationDate)) {
                $occupationDateData = $occupationDate->toArray();
                $occupationDateData['status'] = 'cancelled';
                $occupationDate->update($occupationDateData);
            }

            dispatch(new SendContractCancelledEmail($contract, $data['user_id']));

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'application_form_cancel_error',
                'message' => $e,
            ], 422);

        }

    }

    /**
     * Get a validator for an incoming store request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            'sa_id_number'                   => 'required_if:passport_number,""',
            'passport_number'                => 'required_if:sa_id_number,""',
            'dob'                            => 'required|date',
            'nationality'                    => 'required',
            'phone_mobile'                   => 'sometimes',
            'phone_home'                     => 'sometimes',
            'phone_work'                     => 'sometimes',
            'current_address'                => 'required',
            'marital_status'                 => 'required',
            'married_type'                   => 'required_if:marital_status,"Married"',
            'current_property_owner'         => 'required',
            'rental_time'                    => 'required_if:current_property_owner,false|in:12, 24, 36, 48|nullable',
            'rental_amount'                  => 'required_if:current_property_owner,false|integer|nullable',
            'rental_name'                    => 'required_if:current_property_owner,false|max:191',
            'rental_phone_home'              => 'required_if:current_property_owner,false|max:191',
            'rental_phone_mobile'            => 'required_if:current_property_owner,false|max:191',
            'selfemployed'                   => 'required|boolean',
            'occupation'                     => 'required',
            'current_monthly_expenses'       => 'required',
            'employer_company'               => 'required_if:selfemployed,false|max:191',
            'employer_name'                  => 'required_if:selfemployed,false|max:191',
            'employer_phone_work'            => 'required_if:selfemployed,false|max:191',
            'employer_email'                 => 'required_if:selfemployed,false|max:191',
            'employer_salary'                => 'required_if:selfemployed,false|max:191',
            'resident_first_name'            => 'required|max:191',
            'resident_last_name'             => 'required|max:191',
            'resident_sa_id_number'          => 'required_if:resident_passport_number,""|max:191',
            'resident_passport_number'       => 'required_if:resident_sa_id_number,""|max:191',
            'resident_dob'                   => 'required|date',
            'resident_nationality'           => 'required|max:191',
            'resident_phone_mobile'          => 'required|max:191',
            'resident_email'                 => 'required|email|max:191',
            'resident_current_address'       => 'required|max:191',
            'resident_landlord'              => 'required|max:191',
            'resident_landlord_phone_work'   => 'required|max:191',
            'resident_landlord_phone_mobile' => 'required|max:191',
            'resident_study_location'        => 'required|max:191',
            'resident_study_year'            => 'required|numeric|max:10',
            'resident_gender'                => 'required|in:Male,Female,Unlisted',
            'unit_location'                  => 'required',
            'unit_type'                      => 'required',
            'unit_lease_length'              => 'required',
            // 'unit_car_parking'               => 'required',
            // 'unit_bike_parking'              => 'required',
            // 'unit_tv'                        => 'required',
            // 'unit_storeroom'                 => 'required',
            'unit_occupation_date'           => 'required',
            'judgements'                     => 'required|boolean',
            'judgements_details'             => 'required_if:judgements,1',
            'resident_id'                    => 'required',
            'resident_study_permit'          => 'required',
            'resident_acceptance'            => 'required',
            'resident_financial_aid'         => 'required',
            'leaseholder_id'                 => 'required',
            'leaseholder_address_proof'      => 'required',
            'leaseholder_payslip'            => 'required'
        ]);
    }

    /**
     * Get a validator step 7 (images) update request
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function step7_validator(array $data)
    {

        return Validator::make($data, [
            'resident_id'               => 'required',
            'resident_study_permit'     => 'required',
            'resident_acceptance'       => 'required',
            'resident_financial_aid'    => 'required',
            'leaseholder_id'            => 'required',
            'leaseholder_address_proof' => 'required',
            'leaseholder_payslip'       => 'required'
        ]);
    }

}
