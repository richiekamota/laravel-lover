<?php

namespace Portal\Http\Controllers;

use Auth;
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function storeApplicationDocument( Request $request )
    {

        $validator = Validator::make( $request->all(), [
            'document_type' => 'required',
        ] );

        if ( $validator->fails() ) {
            return Response::json([
                'message' => trans('portal.documents_store_error')
            ], 422);
        }

        // A document must only be uploaded to the users latest application
        $application = Application::whereUserId( Auth::user()->id )
            ->orderBy( 'created_at', 'desc' )
            ->first();

        abort_unless( $application, 403 );

        $type = $request->document_type;

        // Save the file into storage
        $path = $request->file->store( 'documents' );
        $fileName = str_replace( 'documents/', '', $path );
        // Split at . to leave only name left
        $fileNameArray = explode( '.', $fileName );

        $document = Document::create( [
            'user_id'       => Auth::user()->id,
            'location'      => $fileName,
            'document_type' => $type,
            'file_name'     => $fileNameArray[0]
        ] );

        $application[ $type ] = $document->id;
        $application->save();

        return response( 200 );

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function returnDocument( $id )
    {

        $document = Document::whereFileName( $id )->first();

        // abort unless Auth owns the doc ID
        abort_unless( $document->user_id == Auth::user()->id, 403 );

        // serve the doc back as a download
        $storagePath = Storage::disk( 'local' )->getDriver()->getAdapter()->getPathPrefix();

        \Log::info($storagePath);

        return response()->download( $storagePath . "documents/" . $document->location );

    }

    ///**
    // * Remove the specified resource from storage.
    // *
    // * @param  int  $id
    // * @return \Illuminate\Http\Response
    // */
    //public function destroy($id)
    //{
    //    //
    //}
}
