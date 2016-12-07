<?php

namespace Portal\Http\Controllers;

use Auth;
use DB;
use PDF;
use Illuminate\Http\Request;
use Portal\Contract;
use Portal\ContractItem;
use Portal\Http\Requests\ContractCreateRequest;
use Portal\Jobs\SendContractToUserEmail;
use Portal\User;
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
            $pdfName = ucfirst( preg_replace( '/[^\w-]/', '', Auth::user()->first_name ) ) . ucfirst( preg_replace( '/[^\w-]/', '', Auth::user()->last_name ) ) . \Carbon\Carbon::today()->toDateString();

            $data = [ 'name' => Auth::user()->first_name ];
            $filePath = storage_path( 'contracts/' . $pdfName . '.pdf' );
            $pdf = PDF::loadView( 'pdf.contract', $data )->save( $filePath );

            // TODO Save the PDF into S3

            $user = User::findOrFail( $request->user_id );
            // Generate a secure link for the user_id passed in
            $contract->secure_link = encrypt( $user->email . '##' . $filePath );
            $contract->save();

            // Save the items array into the contract so we
            // have a structured list of the items for this
            // specific contract
            if ( $request->items ) {
                foreach ( $request->items as $item ) {

                    // Create the contract item
                    ContractItem::create( [
                        'contract_id' => $contract->id,
                        'name'        => $item['name'],
                        'description' => $item['description'],
                        'value'       => $item['cost']
                    ] );

                }
            }

            // Generate the secure return email for this contract
            dispatch( new SendContractToUserEmail( $filePath, $request->user_id, $contract->secure_link ) );

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
     * Show a user a copy of the contract for approval.
     *
     * @param $secureLink
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show( $secureLink )
    {

        // Decrypt the link
        try {
            $decrypted = decrypt( $secureLink );
            $secureArray = explode( "##", $decrypted );

            abort_unless( $secureArray[0] == Auth::user()->email, 401 );

            // Find the secureLink in the DB
            $contract = Contract::whereSecureLink( $secureLink )->first();

            // Check the secure link user_id matches that of the Auth::user()
            abort_unless( $contract->user_id == Auth::user()->id, 401 );

            // Return the use the view with their contract details
            return view( 'contracts.review', compact( 'contract' ) );


        } catch ( DecryptException $e ) {
            abort( 500 );
        }

        // abort unless Auth > tenant


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
