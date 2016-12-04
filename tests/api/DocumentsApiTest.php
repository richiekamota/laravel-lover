<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Portal\Application;
use Portal\Document;

class DocumentsApiTest extends TestCase
{

    use DatabaseMigrations;

    /*
     * Test an document upload fails when
     * a user has no applications
     */
    public function testFailValidationAsTenant()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'tenant'
        ] );

        $file = $this->getImageSetup();

        $this->actingAs( $user );
        $response = $this->call(
            'POST',
            'documents/application',
            [],
            [],
            [],
            [ 'file' => $file ]
        );

        $this->assertNotEquals( $response->getStatusCode(), 200 );

    }

    /**
     * Generate the image to be used for testing
     */
    private function getImageSetup()
    {
        $path = storage_path( 'testing/fff.png' );
        $original_name = 'fff.png';
        $mime_type = 'image/png';
        $size = 1800;
        $error = NULL;
        $test = TRUE;

        return new \Illuminate\Http\UploadedFile( $path, $original_name, $mime_type, $size, $error, $test );
    }

    /**
     * Test that an uploaded file has been linked to an application
     * and is listed in the DB
     */
    public function testUploadPassesWhenTenantHasApplication()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'tenant'
        ] );
        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $file = $this->getImageSetup();

        $values = [
            'document_type' => 'resident_id'
        ];

        $this->actingAs( $user );
        $this->action(
            'POST',
            'DocumentsController@storeApplicationDocument',
            [],
            $values,
            [],
            [ 'file' => $file ]
        );

        // Check there is a document in the DB
        $this->seeInDatabase( 'documents', [
            'document_type' => $values['document_type'],
            'user_id'       => $user->id
        ] );

        // Find the updated application
        $updatedApplication = Application::find( $application->id );

        // Check that the field resident_id is not empty, this
        // will have been filled in with the id of the document
        $this->assertNotEquals( $updatedApplication->resident_id, NULL );

        $this->assertResponseOk();

    }

    /**
     * Test that a user can download a given file URL
     * if they are the owner of that file
     */
    public function testUserCanDownloadFileWithId()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'tenant'
        ] );

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $file = $this->getImageSetup();

        $values = [
            'document_type' => 'resident_id'
        ];

        $this->actingAs( $user );
        $this->action(
            'POST',
            'DocumentsController@storeApplicationDocument',
            [],
            $values,
            [],
            [ 'file' => $file ]
        );

        $document = Document::all()->first();

        \Log::info( $document );

        $this->call( 'GET', 'documents/' . $document->file_name );

        $this->assertResponseOk();

    }

}