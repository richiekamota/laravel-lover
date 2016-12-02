<?php

namespace Portal\Http\Controllers;

use Auth;
use DB;
use PDF;
use Illuminate\Http\Request;
use Portal\Contract;
use Portal\Http\Requests\ContractCreateRequest;
use Response;

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
     * Store a newly created resource in storage.
     *
     * @param Request|ContractCreateRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store( ContractCreateRequest $request )
    {

        // about unless Auth is level above tenant
        $this->authorize( 'create', Contract::class );

        DB::beginTransaction();

        try {

            // Take the request and store in the DB
            $contract = Contract::create( $request->all() );

            // Generate a PDF of the contract based on the application
            // The name should be the user first and last name and the date
            // eg FirstName20160424.pdf
            $pdfName = ucfirst(preg_replace('/[^\w-]/', '', Auth::user()->first_name)) . ucfirst(preg_replace('/[^\w-]/', '', Auth::user()->last_name)) . \Carbon\Carbon::today()->toDateString();

            $data = [ 'name' => Auth::user()->first_name ];
            $pdf = PDF::loadView( 'pdf.contract', $data )->save(storage_path('contracts/' . $pdfName . '.pdf'));

            // Save this into S3

            // Generate the secure return email for this contract

            // Send the applicant a copy of the PDF in email

            DB::commit();

            return Response::json( [
                'message' => trans( 'portal.contracts_store_complete' ),
                'data'    => $contract->toArray()
            ], 200 );

        } catch ( \Exception $e ) {

            \Log::info( $e );

            //Bugsnag::notifyException($e);

            DB::rollback();

            return Response::json( [
                'error'   => 'contracts_store_error',
                'message' => trans( 'portal.contracts_store_error' ),
            ], 422 );

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {

        // abort unless Auth > tenant

        // return view('contract.show');

    }

    /**
     * We should not be editing the contract
     * only creating and removing
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    //public function edit($id)
    //{
    //    //
    //}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {

        // abort unless Auth > tenant

        // soft delete the record

    }
}
