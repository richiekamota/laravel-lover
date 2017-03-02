<?php

namespace Portal\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Portal\Http\Requests\ItemCreateRequest;
use Portal\Http\Requests\ItemEditRequest;
use Portal\Item;
use Portal\UnitType;
use Response;

class ItemsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // abort unless Auth > tenant
        $this->authorize('view', Item::class);

        $items = Item::with('unitTypes')->get();

        $unitTypes = UnitType::all();

        return view('items.index', compact('items', 'unitTypes'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|ItemsController|ItemCreateRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store( ItemCreateRequest $request )
    {

        // abort unless Auth > tenant
        $this->authorize('create', Item::class);

        DB::beginTransaction();

        try {

            // Remove the array of ids from the input
            $unit_types = $request->input('unit_types');

            $data = $request->all();
            unset($data['unit_types']);

            // Store the location in the DB
            $item = Item::create($data);

            // Attach the unit types that have this item
            $item->unitTypes()->attach($unit_types);

            DB::commit();

            return Response::json([
                'message' => trans('portal.items_store_complete'),
                'data' => Item::with('unitTypes')->find($item->id)->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info( $e );

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json( [
                'error'   => 'items_store_error',
                'message' => trans( 'portal.items_store_error' ),
            ], 422 );

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|ItemEditRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update( ItemEditRequest $request, $id )
    {

        // abort unless Auth > tenant
        $this->authorize('update', Item::class);

        DB::beginTransaction();

        try {

            $item = Item::findOrFail($id);

            $unit_types = $request->input('unit_types');

            $data = $request->all();
            unset($data['unit_types']);

            // Store the location in the DB
            $item->update($request->all());

            // Remove any connections to this item
            $item->unitTypes()->detach();

            // Attach the unit type to this item
            $item->unitTypes()->attach($unit_types);

            DB::commit();

            return Response::json([
                'message' => trans('portal.items_store_complete'),
                'data' => $item->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info( $e );

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json( [
                'error'   => 'items_store_error',
                'message' => trans( 'portal.items_store_error' ),
            ], 422 );

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
