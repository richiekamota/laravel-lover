<?php

namespace Portal\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Portal\Http\Requests\ItemCreateRequest;
use Portal\Http\Requests\ItemEditRequest;
use Portal\Http\Requests\ItemLeaseCreateRequest;
use Portal\Item;
use Portal\User;
use Portal\UnitType;
use Portal\ItemLeaseDate;
use Gate;
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

        $items = Item::with('unitTypes')->orderBy('name', 'ASC')->get();

        $unitTypes = UnitType::all();

        return view('items.index', compact('items', 'unitTypes'));

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request|ItemsController|ItemCreateRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(ItemCreateRequest $request)
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

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error' => 'items_store_error',
                'message' => trans('portal.items_store_error'),
            ], 422);

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|ItemEditRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(ItemEditRequest $request, $id)
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

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error' => 'items_store_error',
                'message' => json_encode($e),
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

        //

    }



    /**
     * Display a leased items
     *
     * @return \Illuminate\Http\Response
     */
    public function leasedItems()
    {

        // abort unless Auth > tenant
        $this->authorize('view', Item::class);

        $items = Item::with('ItemLeaseDates')->where("for_lease",1)->get();
        $unitTypes = UnitType::all();
        $users = User::all();
        // print_r($items);

        return view('items.leased-items', compact('items', 'unitTypes', 'users'));

    }

    /**
     * Create a item lease
     *
     * @return \Illuminate\Http\Response
     */
    public function createItemLease(ItemLeaseCreateRequest $request)
    {

        // abort unless Auth > tenant
        $this->authorize('create', Item::class);
        $leases = ItemLeaseDate::where("item_name", $request->item_name)
            ->whereBetween('start_date', array($request->start_date, $request->end_date))
            ->whereBetween('end_date', array($request->start_date, $request->end_date))
            ->get();

        if(count($leases) > 0){
            return Response::json( [
                'error'   => 'items_lease_store_error',
                'message' => trans( 'Error creating item lease. This item is not available for the selected date period.' ),
            ], 422 );
        }

        DB::beginTransaction();

        try {

            $data = array(
                "leasee_name" => $request->leasee_name,
                "user_id" => $request->user_id,
                "item_name" => $request->item_name,
                "item_id" =>  $request->item_id,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                "status" => 'Active'
            );

            // Store the location in the DB
            $itemLease = ItemLeaseDate::create($data);

            DB::commit();

            return Response::json([
                'message' => trans('Item lease created.'),
                'data' => $itemLease->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info( $e );

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json( [
                'error'   => 'items_lease_store_error',
                'message' =>$e,
            ], 422 );

        }

    }

    /**
     * Delete a item lease
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteItemLease($id)
    {

        // abort unless Auth > tenant
        $this->authorize('delete', Item::class);

        DB::beginTransaction();

        try {

            // Remove the array of ids from the input
            $lease_dates = ItemLeaseDate::where("id",$id)->first();

            DB::table('item_lease_dates')->where('id', $id)->delete();

            DB::commit();

            return Response::json([
                'message' => trans('Item lease deleted.'),
                'data' => $lease_dates->toArray()
            ], 200);

        } catch (\Exception $e) {

            \Log::info( $e );

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json( [
                'error'   => 'items_lease_delete_error',
                'message' => trans( 'Error deleting item lease.' ),
            ], 422 );

        }

    }

    /**
     * Delete an item
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteItem(Request $request)
    {

        // abort unless Auth is tenant
        abort_unless(Gate::allows('is-admin'), 401);

        if(Gate::denies('is-tenant') || Gate::denies('is-not-tenant')){
        return;
        }

        DB::beginTransaction();

        try {

            $item = Item::findOrFail($request->id);

            $item->delete();

            DB::commit();

            return Response::json([
                'message' => trans('portal.item_deleted')
            ], 200);

        } catch (\Exception $e) {

            \Log::info( $e );// Live testing logging  info

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json( [
                'error'   => 'item_delete_error',
                'message' => trans( 'portal.item_delete_error' ),
            ], 422 );
        }
    }
}
