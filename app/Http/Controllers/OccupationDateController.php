<?php

namespace Portal\Http\Controllers;

use Gate;
use DB;
use Illuminate\Http\Request;
use Portal\OccupationDate;
use Response;

class OccupationDateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        abort_unless(Gate::allows('is-admin'), 401);

        $occupationDates = OccupationDate::with('user', 'unit', 'items', 'application')->all();

        print_r($occupationDates);

        return view( 'occupation-dates.index', compact('occupationDates' ) );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|UnitCreateRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store( OccupationDateRequest $request )
    {

        // abort unless Auth > tenant
        $this->authorize('create', OccupationDate::class);

        DB::beginTransaction();

        try {

            // Store the location in the DB
            $occupationDate = OccupationDate::create($request->all());

            DB::commit();

            return Response::json([
                'message' => trans('portal.occupation_date_store_complete'),
                'data' => $occupationDate->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'occupation_date_store_error',
                'message' => trans('portal.occupation_date_error'),
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
    public function destroy( $id )
    {
        //
    }
}
