<?php

namespace Portal\Http\Controllers;

use Auth;
use DB;
use Gate;
use Illuminate\Http\Request;
use Portal\Http\Requests\ContractAmendmentRequest;
use Portal\ContractAmendment;
use Portal\Application;
use Portal\Document;
use Response;
use Storage;
use Validator;

class DocumentsController extends Controller
{

    /**
     * Store a document in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function storeApplicationDocument(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'document_type' => 'required',
            'file' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'message' => json_encode($validator->errors())
            ], 422);
        }

        // A document must only be uploaded to the users latest application
        $application = Application::find($request->id);

        abort_unless($application, 403);

        DB::beginTransaction();

        // The type of document the user has uploaded
        // eg 
        $type = $request->document_type;

        // Save the file into storage
        $path = Storage::putFile('documents', $request->file);
        // The new name of the file in storage
        $fileName = str_replace('documents/', '', $path);

        // Save the document to the DB
        $document = Document::create([
            'user_id'   => Auth::user()->id,
            'location'  => $path,
            'type'      => $type,
            'file_name' => $fileName
        ]);

        $application[$type] = $document->id;
        $application->save();

        DB::commit();

        return response(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function returnDocument($id)
    {

        $document = Document::findOrFail($id);

        // Abort unless the user is an admin or uploaded the document
        abort_unless(($document->user_id === Auth::user()->id || Auth::user()->role != 'tenant'), 403);

        // Get the details from Storage, then stream them back to the 
        // requester. This ensures the file path is not released
        // to the client and the S3 bucket security is maintained
        $s3Client = Storage::getAdapter()->getClient();
        $stream = $s3Client->getObject([
            'Bucket' => env('S3_BUCKET'),
            'Key'    => $document->location
        ]);
        return response($stream['Body'], 200)->withHeaders([
            'Content-Type'        => $stream['ContentType'],
            'Content-Length'      => $stream['ContentLength'],
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"'
        ]);

    }

    /**
     * Store an amended document in storage
     *
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function storeContractAmendmentDocument(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file'        => 'required',
            'contract_id' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'message' => json_encode($validator->errors())
            ], 422);
        }

        // abort unless Auth > tenant
        abort_unless(Gate::allows('is-admin'), 401);

        DB::beginTransaction();

        try {

            // Save the file into storage
            $path = Storage::putFile('amendedContracts', $request->file);

            // Save the file into storage
            // $path = $request->file->store('amendedContracts');
            $fileName = str_replace('amendedContracts/', '', $path);

            $document = Document::create([
                'user_id'   => Auth::user()->id,
                'location'  => $path,
                'type'      => 'contract_amendment',
                'file_name' => $fileName
            ]);

            $amendment = ContractAmendment::create([
                'document_id'  => $document->id,
                'contract_id'  => $request->contract_id
            ]);

            DB::commit();

            return Response::json([
                'message' => trans('portal.contracts_amendment_complete')
            ], 200);

        } catch (\Exception $e) {

            DB::rollback();

            return Response::json([
                'error'   => 'documents_store_error',
                'message' => trans('portal.documents_store_error' . json_encode($e)),
            ], 422);
        }
    }
}