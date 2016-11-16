<?php

namespace Portal\Http\Controllers;

use Illuminate\Http\Request;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Show a list of all the contracts
        // return view('contracts.index');

    }

    /**
     * Return a JSON listing of the resource
     * that belongs to this users based on passed in ID
     * of the current Authed users.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForUser()
    {

        // Find all the contracts for the Auth user

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // abort unless Auth is level above tenant

        // return view('contracts.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // about unless Auth is level above tenant

        DB::beginTransaction();

        try {

            // Take the request and store in the DB

            // Generate a PDF of the contract based on the application

            // Save this into S3

            // Generate the secure return email for this contract

            // Send the applicant a copy of the PDF in email

            DB::commit();

        } catch (\Exception $e) {

            \Log::info($e);

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json([
                'error'   => 'contracts_store_error',
                'message' => trans('portal.contracts_store_error'),
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

        // abort unless Auth > tenant

        // return view('contract.show');

    }

    /**
     * We should not be editing the contract
     * only creating and removing
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function edit($id)
    //{
    //    //
    //}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // abort unless Auth > tenant

        // soft delete the record

    }
}
