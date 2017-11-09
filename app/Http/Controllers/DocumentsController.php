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

        $type = $request->document_type;

        // Save the file into storage
        $path = $request->file->store('documents');
        $fileName = str_replace('documents/', '', $path);
        // Split at . to leave only name left
        $fileNameArray = explode('.', $fileName);

        $document = Document::create([
            'user_id'   => Auth::user()->id,
            'location'  => $fileName,
            'type'      => $type,
            'file_name' => $fileNameArray[0]
        ]);
        $applicationData = $application->toArray();
        $applicationData[$type] = $document->id;
        $application->update($applicationData);

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

        \Log::info($document);

        // abort unless Auth owns the doc ID
        // abort_unless(($document->user_id == Auth::user()->id || Auth::user()->role != 'tenant'), 403);

        if ($document->type == "contract") {
            return response()->download(storage_path('contracts/' . $document->location));
        }

        // serve the doc back as a download
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

        return response()->download($storagePath . "documents/" . $document->location);

    }

}
