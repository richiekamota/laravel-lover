<?php

namespace Portal\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
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
            'Bucket' => 'swish-portal',
            'Key'    => $document->location
        ]);
        return response($stream['Body'], 200)->withHeaders([
            'Content-Type'        => $stream['ContentType'],
            'Content-Length'      => $stream['ContentLength'],
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"'
        ]);

    }

}
