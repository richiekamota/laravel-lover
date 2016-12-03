<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Portal\Jobs\SendContractToUserEmail;
use MailThief\Testing\InteractsWithMail;

class ContractsApiTest extends TestCase
{

    use DatabaseMigrations;

    // Provides convenient testing traits and initializes MailThief
    use InteractsWithMail;

    ///*
    // * Test an contract submission fails on user id validation
    // */
    //public function testFailUserIdValidation()
    //{
    //
    //    $user = factory( Portal\User::class )->create( [
    //        'role' => 'application'
    //    ] );
    //
    //    $this->actingAs( $user )
    //        ->json( 'POST', '/contracts', [] )
    //        ->assertResponseStatus( 422 )
    //        ->seeJson( [
    //            "user_id" => [ "The user id field is required." ]
    //        ] );
    //
    //}
    //
    ///*
    // * Test an contract submission fails on unit id validation
    // */
    //public function testFailUnitIdValidation()
    //{
    //
    //    $user = factory( Portal\User::class )->create( [
    //        'role' => 'application'
    //    ] );
    //
    //    $this->actingAs( $user )
    //        ->json( 'POST', '/contracts', [
    //            'user_id' => $user->id
    //        ] )
    //        ->assertResponseStatus( 422 )
    //        ->seeJson( [
    //            "unit_id" => [ "The unit id field is required." ]
    //        ] );
    //
    //}
    //
    ///*
    // * Test an contract submission fails on start date validation
    // */
    //public function testFailStartDateValidation()
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
    //            'user_id' => $user->id,
    //            'unit_id' => $unit->id,
    //        ] )
    //        ->assertResponseStatus( 422 )
    //        ->seeJson( [
    //            "start_date" => [ "The start date field is required." ]
    //        ] );
    //
    //}
    //
    ///*
    // * Test an contract submission fails on end date validation
    // */
    //public function testFailEndDateValidation()
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
    //            'start_date' => \Carbon\Carbon::now()
    //        ] )
    //        ->assertResponseStatus( 422 )
    //        ->seeJson( [
    //            "end_date" => [ "The end date field is required." ]
    //        ] );
    //
    //}
    //
    ///*
    // * Test an item submission fails on unit type validation
    // */
    //public function testPassValidation()
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
    //}
    //
    ///**
    // * Test for PDF creation
    // */
    //public function testPDFIsGenerated()
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
    //    $pdfName = ucfirst( preg_replace( '/[^\w-]/', '', $user->first_name ) ) . ucfirst( preg_replace( '/[^\w-]/', '', $user->last_name ) ) . \Carbon\Carbon::today()->toDateString();
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
    //    $uploaded = 'contracts' . DIRECTORY_SEPARATOR . $pdfName . '.pdf';
    //    $this->assertFileExists( storage_path( $uploaded ) );
    //
    //    // remove the file after the test
    //    unlink( storage_path( $uploaded ) );
    //}
    //
    ///**
    // * Check email sent is correct
    // */
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
    //}

    /**
     * Test that user with a secure link can view their contract
     */
    public function testUserCanViewContract()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );
        $location = factory( Portal\Location::class )->create();
        $unitType = factory( Portal\UnitType::class )->create( [
            'location_id' => $location->id
        ] );
        $unit = factory( Portal\Unit::class )->create( [
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ] );
        $secureLink = encrypt($user->email . '##' . $user->first_name);
        $contract = factory(Portal\Contract::class)->create([
            'unit_id' => $unit->id,
            'user_id' => $user->id,
            'secure_link' => $secureLink
        ]);

        $this->visit('/contracts/secure/' . $secureLink)
            ->see('Your Contract ' . $user->first_name);

    }

}