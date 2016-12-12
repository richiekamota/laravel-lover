<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Portal\Jobs\SendContractToUserEmail;
use MailThief\Testing\InteractsWithMail;

class ApplicationProcessTest extends TestCase
{

    use DatabaseMigrations;

    // Provides convenient testing traits and initializes MailThief
    use InteractsWithMail;

    /**
     * Setup the tests on this page
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->userTenant = factory( Portal\User::class )->create( [
            'role' => 'tenant'
        ] );

        $this->application = factory(Portal\Application::class)->states('forApproval')->create([
            'user_id' => $this->userTenant->id
        ]);


    }

    /**
     * Test that a user can view the page
     */
    public function testCanSeeReviewPage()
    {

        $this->actingAs( $this->user )
            ->visit('/application/'. $this->application->id.'/review')
            ->assertResponseStatus(200);

    }

    /*
     * Test an contract submission fails on user id validation
     */
    public function testFailDeclineApplicationWithoutReason()
    {

        $this->actingAs( $this->user )
            ->json( 'POST', '/application/' . $this->application->id . '/decline', [
                'reason' => ''
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "reason" => [ "The reason field is required." ]
            ] );

    }

    /**
     * Test that a declined application with a reason
     * was accepted and that an event was created
     */
    public function testDeclineApplication()
    {

        $this->actingAs( $this->user )
            ->json( 'POST', '/application/' . $this->application->id . '/decline', [
                'reason' => 'This is the reason'
            ] )
            ->assertResponseStatus( 200 )
            ->seeInDatabase( 'applications', [
                'id' => $this->application->id,
                'status' => 'declined'
            ] )
            ->seeInDatabase( 'application_events', [
                'application_id' => $this->application->id,
                'user_id' => $this->user->id,
                'action' => 'Application declined',
                'note' => 'This is the reason'
            ] );

    }

    /**
     * Check email sent is correct
     */
    //public function testSecureContractEmailContents()
    //{
    //
    //    $user = factory( Portal\User::class )->create( [
    //        'role' => 'application'
    //    ] );
    //    $location = factory( Portal\Location::class )->create();
    //    $unitType = factory( Portal\UnitType::class )->create( [
    //        'location_id' => $location->id
    //    ] );
    //    $unit = factory( Portal\Unit::class )->create( [
    //        'location_id' => $location->id,
    //        'type_id'     => $unitType->id
    //    ] );
    //
    //    $this->actingAs( $user )
    //        ->json( 'POST', '/contracts', [
    //            'user_id'    => $user->id,
    //            'unit_id'    => $unit->id,
    //            'start_date' => '2016-01-01',
    //            'end_date'   => '2016-11-01',
    //        ] )
    //        ->assertResponseStatus( 200 );
    //
    //    // Check that an email was sent to this email address
    //    $this->seeMessageFor($user->email);
    //
    //    // Make sure the email has the correct subject
    //    $this->seeMessageWithSubject('My Domain contract for review');
    //
    //    // Make sure the email was sent from the correct address
    //    $this->seeMessageFrom('noreply@mydomain.co.za');
    //
    //    // Make sure the email contains text in the body of the message
    //    // Default is to search the html rendered view
    //    $this->assertTrue($this->lastMessage()->contains('Here is the latest contract for your approval'));
    //
    //    $pdfName = ucfirst( preg_replace( '/[^\w-]/', '', $user->first_name ) ) . ucfirst( preg_replace( '/[^\w-]/', '', $user->last_name ) ) . \Carbon\Carbon::today()->toDateString();
    //    $uploaded = 'contracts' . DIRECTORY_SEPARATOR . $pdfName . '.pdf';
    //    unlink( storage_path( $uploaded ) );
    //
    //}

}