<?php

namespace Portal\Http\Controllers;

use DB;
use Illuminate\Http\Request;

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

        return view('items.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // abort unless Auth > tenant

        // return view('items.create);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {

        // abort unless Auth > tenant   

        DB::beginTransaction();

        try {

            // store the record in the DB

            // return ok

            DB::commit();

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

    ///**
    // * Display the specified resource.
    // *
    // * @param  int $id
    // *
    // * @return \Illuminate\Http\Response
    // */
    //public function show( $id )
    //{
    //    //
    //}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {

        // abort unless Auth > tenant

        // return view('items.edit');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {

        // abort unless Auth > tenant

        DB::beginTransaction();

        try {

            // Save the updated resource into the DB

            DB::commit();

        } catch (\Exception $e) {

            \Log::info( $e );

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json( [
                'error'   => 'items_update_error',
                'message' => trans( 'portal.items_update_error' ),
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

        // abort unless Auth > tenant

        DB::beginTransaction();

        try {

            // Soft delete the item

            DB::commit();

        } catch (\Exception $e) {

            \Log::info( $e );

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json( [
                'error'   => 'items_delete_error',
                'message' => trans( 'portal.items_delete_error' ),
            ], 422 );

        }

    }
}
