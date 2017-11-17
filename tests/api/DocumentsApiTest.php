<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\BrowserKitTesting\Concerns\InteractsWithDatabase;
use Laravel\BrowserKitTesting\Concerns\MakesHTTPRequests;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Portal\Application;
use Portal\Document;

class DocumentsApiTest extends Tests\TestCase
{

    use DatabaseTransactions;
    use InteractsWithDatabase;

    /*
     * Test an document upload fails when
     * a user has no applications
     */
    public function testFailValidationAsTenant()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'tenant'
        ]);

        $file = $this->getImageSetup();

        $this->actingAs($user);
        $response = $this->call(
            'POST',
            'documents/application',
            [],
            [],
            [],
            ['file' => $file]
        );

        $this->assertEquals($response->getStatusCode(), 422);

    }

    /**
     * Generate the image to be used for testing
     */
    private function getImageSetup()
    {
        $path = storage_path('testing/fff.png');
        $original_name = 'fff.png';
        $mime_type = 'image/png';
        $size = 1800;
        $error = NULL;
        $test = TRUE;

        return new \Illuminate\Http\UploadedFile($path, $original_name, $mime_type, $size, $error, $test);
    }

    /**
     * Test that an uploaded file has been linked to an application
     * and is listed in the DB
     */
    public function testUploadPassesWhenTenantHasApplication()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'tenant'
        ]);
        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $file = $this->getImageSetup();

        $values = [
            'document_type' => 'resident_id',
            'id' => $application->id
        ];

        $response = $this->actingAs( $user )->call(
            'POST',
            'documents/application',
            $values,
            [],

            [ 'file' => $file ],
            [],
            []
        );

        $this->assertEquals($response->getStatusCode(), 200);

        // Check there is a document in the DB
        $this->actingAs( $user )->seeInDatabase('documents', [
            'type'    => $values['document_type'],
            'user_id' => $user->id
        ]);

        // Find the updated application
        $updatedApplication = Application::find($application->id);

        // Check that the field resident_id is not empty, this
        // will have been filled in with the id of the document
        $this->assertNotEquals($updatedApplication->resident_id, NULL);

    }

    /**
     * Test that a user can download a given file URL
     * if they are the owner of that file
     * @group failing
     */
    public function testUserCanDownloadFileWithId()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'tenant'
        ]);

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        // Setup Image in the DB to test against
        $file = $this->getImageSetup();

        $values = [
            'document_type' => 'resident_id',
            'id' => $application->id
        ];

        $response = $this->actingAs( $user )->call(
            'POST',
            'documents/application',
            $values,
            [],

            [ 'file' => $file ],
            [],
            []
        );
        // END of document setup

        $document = Document::whereUserId($user->id)->first();

        $this->actingAs($user);
        $this->call('GET', "documents/" . $document->id);
        $this->assertResponseStatus(200);

    }

    /**
     * Test that an uploaded file has been linked to an application
     * and is listed in the DB
     */
    public function testUploadPassesWhenContractIsAmended()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'admin'
        ]);

        $contract = factory(Portal\Contract::class)->create([
            'user_id' => $user->id
        ]);

        $file = $this->getImageSetup();

        $values = [
            'document_type' => 'contract_amendment',
            'contract_id' => $contract->id
        ];

        //  $this->actingAs($user)->json('POST', '/contracts', $values);

        $this->actingAs( $user );
        $response = $this->actingAs( $user )->call(
            'POST',
            '/documents/amendment',
            $values,
            [],
            [ 'file' => $file ],
            [],
            []
        );

        $this->assertEquals($response->getStatusCode(), 200);

        // Check there is a document in the DB
        $this->actingAs( $user )->seeInDatabase('documents', [
            'type'    => $values['document_type'],
            'user_id' => $user->id
        ]);

        $this->actingAs( $user )->seeInDatabase('contract_amendments', [
            'contract_id' => $contract->id
        ]);
    }

    /*
     * Tests that a document upload fails when
     * a user is not admin
     */
    public function testUploadFailsWhenUserIsNotAdmin()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'tenant'
        ]);

        $file = $this->getImageSetup();

        $this->actingAs($user);
        $response = $this->call(
            'POST',
            'documents/amendment',
            [],
            [],
            [],
            ['file' => $file]
        );

        $this->assertEquals($response->getStatusCode(), 422);
    }
}