<?php

namespace Portal\Http\Controllers;

use DB;
use Portal\Http\Requests\UnitTypeCreateRequest;
use Portal\Http\Requests\UnitTypeEditRequest;
use Portal\Location;
use Portal\UnitType;
use Response;

class UnitTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // abort unless Auth > tenant
        $this->authorize('view', UnitType::class);

        $unitTypes = UnitType::all();
        $locations = Location::all();

        return view('unit-types.index', compact('unitTypes', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UnitTypeCreateRequest $request)
    {

        // abort unless Auth > tenant
        $this->authorize('create', UnitType::class);

        DB::beginTransaction();

        try {

            // Store the location in the DB
            $unitType = UnitType::create($request->all());

            DB::commit();

            return Response::json([
                'message' => trans('portal.unitType_store_complete'),
                'data' => $unitType->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'unitType_store_error',
                'message' => trans('portal.unitType_store_error'),
            ], 422);

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UnitTypeEditRequest $request, $id)
    {

        // abort unless Auth > tenant
        $this->authorize('update', UnitType::class);

        DB::beginTransaction();

        try {

            UnitType::findOrFail($id)->update($request->all());

            DB::commit();

            return Response::json([
                'message' => trans('portal.unitType_edit_complete'),
                'data' => UnitType::findOrFail($id)->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'unitType_edit_error',
                'message' => trans('portal.unitType_edit_error'),
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
        //
    }
}
