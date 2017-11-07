<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class StepTwoApiTest extends Tests\TestCase
{
    use DatabaseMigrations;

    public function testStepTwoFailValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
        ->json( 'POST', '/step-2/' . $application->id, [] )
        ->assertResponseStatus( 422 );

    }

    public function testStepTwoFailRentalValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
        ->json( 'POST', '/step-2/' . $application->id, [
            'current_property_owner' => 'false',
        ] )
        ->assertResponseStatus( 422 )
        ->seeJson( [
            "rental_amount" => ["The rental amount field is required when current property owner is false."]
        ] );

    }

    public function testStepTwoFailRentalTimeValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
        ->json( 'POST', '/step-2/' . $application->id, [
            'current_property_owner' => 'false'
        ] )
        ->assertResponseStatus( 422 )
        ->seeJson( [
            "rental_time" => ["The rental time field is required when current property owner is false."]
        ] );

    }

    public function testStepTwoFailRentalAmountValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
        ->json( 'POST', '/step-2/' . $application->id, [
            'current_property_owner' => 'false',
            'rental_time'            => '12',
            'rental_amount'          => 'abc'
        ] )
        ->assertResponseStatus( 422 )
        ->seeJson( [
            "rental_amount" => ["The rental amount must be a number."]
        ] );

    }

    public function testStepTwoPassValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
        ->json( 'POST', '/step-2/' . $application->id, [
            'current_property_owner' => false,
            'rental_time'            => '12',
            'rental_amount'          => '123',
            'rental_name'            => 'Phil Benoit',
            'rental_phone_home'      => '123456789',
            'rental_phone_mobile'    => '123456789',
        ] )
        ->assertResponseStatus( 200 );

    }

}
