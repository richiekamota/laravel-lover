<?php

namespace Portal\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use Portal\Http\Requests\LocationCreateRequest;
use Portal\Http\Requests\LocationEditRequest;
use Portal\Location;
use Response;

class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // abort unless Auth > tenant
        $this->authorize('view', Location::class);

        $locations = Location::all();

        return view('locations.index', compact('locations'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|LocationCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LocationCreateRequest $request)
    {

        // abort unless Auth > tenant
        $this->authorize('create', Location::class);

        DB::beginTransaction();

        try {

            // Store the location in the DB
            $location = Location::create($request->all());

            DB::commit();

            return Response::json([
                'message' => trans('portal.locations_store_complete'),
                'data' => $location->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'locations_store_error',
                'message' => trans('portal.locations_store_error'),
            ], 422);

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|LocationEditRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(LocationEditRequest $request, $id)
    {

        // abort unless Auth > tenant
        $this->authorize('update', Location::class);

        DB::beginTransaction();

        try {

            Location::findOrFail($id)->update($request->all());

            DB::commit();

            return Response::json([
                'message' => trans('portal.locations_edit_complete'),
                'data' => Location::findOrFail($id)->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'locations_edit_error',
                'message' => trans('portal.locations_edit_error'),
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
