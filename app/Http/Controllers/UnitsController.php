<?php

namespace Portal\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Portal\Http\Requests\UnitCreateRequest;
use Portal\Http\Requests\UnitEditRequest;
use Portal\Location;
use Portal\Unit;
use Portal\UnitType;
use Response;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->authorize('view', Unit::class);

        $locations = Location::all();
        $unitTypes = UnitType::all();
        $units = Unit::with('location','unitType')->get();

        return view( 'units.index', compact( 'locations', 'unitTypes', 'units' ) );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|UnitCreateRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store( UnitCreateRequest $request )
    {

        // abort unless Auth > tenant
        $this->authorize('create', Unit::class);

        DB::beginTransaction();

        try {

            // Store the location in the DB
            $unit = Unit::create($request->all());

            DB::commit();

            return Response::json([
                'message' => trans('portal.unit_store_complete'),
                'data' => $unit->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'unit_store_error',
                'message' => trans('portal.unit_store_error'),
            ], 422);

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update( UnitEditRequest $request, $id )
    {

        // abort unless Auth > tenant
        $this->authorize('update', Unit::class);

        DB::beginTransaction();

        try {

            Unit::findOrFail($id)->update($request->all());

            DB::commit();

            return Response::json([
                'message' => trans('portal.unit_edit_complete'),
                'data' => Unit::findOrFail($id)->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'unit_edit_error',
                'message' => trans('portal.unit_edit_error'),
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
