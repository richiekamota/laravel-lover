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
            $unitInsert = Unit::create($request->all());
            $unit = Unit::with('location','unitType')->findOrFail($unitInsert->id);

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

    public function import(){

        $filename = "units.csv";
        $delimiter = ";";

         if (!file_exists($filename) || !is_readable($filename))
            return "Error finding CSV";

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        foreach($data as $unit){
            $unitInsert = Unit::create([
                "location_id" => $unit['location_id'],
                "type_id" => $unit['type_id'],
                "code" => $unit['code']
            ]);
        }

        //print_r($data);
         return 'Success';
    }
}
