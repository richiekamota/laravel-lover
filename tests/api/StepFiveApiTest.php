<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class StepFiveApiTest extends Tests\TestCase
{
    use DatabaseMigrations;

    public function testStepFiveFailValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-5/' . $application->id, [])
            ->assertResponseStatus(422);

    }

    public function testStepFiveFailUnitLocationValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-5/' . $application->id, [
                'unit_location' => ''
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "unit_location" => ["The unit location field is required."]
            ]);

    }

    public function testStepFiveFailUnitTypeValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-5/' . $application->id, [
                'unit_location' => '123456789',
                'unit_type'     => ''
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "unit_type" => ["The unit type field is required."]
            ]);

    }

    public function testStepFiveFailUnitLeaseLengthValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-5/' . $application->id, [
                'unit_location'     => '123456789',
                'unit_type'         => '123456789',
                'unit_lease_length' => '',
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "unit_lease_length" => ["The unit lease length field is required."]
            ]);

    }


    // THESE SHOULD NOT BE REQUIRED AND VALIDATED ???
    /*
    public function testStepFiveFailUnitCarParkingValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-5/' . $application->id, [
                'unit_location'     => '123456789',
                'unit_type'         => '123456789',
                'unit_lease_length' => '12',
                'unit_car_parking'  => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "unit_car_parking" => ["The unit car parking field is required."]
            ] );

    }

    public function testStepFiveFailUnitCarParkingBooleanValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-5/' . $application->id, [
                'unit_location'     => '123456789',
                'unit_type'         => '123456789',
                'unit_lease_length' => '12',
                'unit_car_parking'  => 'false',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "unit_car_parking" => ["The unit car parking field must be true or false."]
            ] );

    }

    public function testStepFiveFailUnitBikeParkingValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-5/' . $application->id, [
                'unit_location'     => '123456789',
                'unit_type'         => '123456789',
                'unit_lease_length' => '12',
                'unit_car_parking'  => false,
                'unit_bike_parking' => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "unit_bike_parking" => ["The unit bike parking field is required."]
            ] );

    }

    public function testStepFiveFailUnitBikeParkingBooleanValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-5/' . $application->id, [
                'unit_location'     => '123456789',
                'unit_type'         => '123456789',
                'unit_lease_length' => '12',
                'unit_car_parking'  => false,
                'unit_bike_parking' => 'false',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "unit_bike_parking" => ["The unit bike parking field must be true or false."]
            ] );

    }

    public function testStepFiveFailUnitTvValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-5/' . $application->id, [
                'unit_location'     => '123456789',
                'unit_type'         => '123456789',
                'unit_lease_length' => '12',
                'unit_car_parking'  => false,
                'unit_bike_parking' => false,
                'unit_tv'           => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "unit_tv" => ["The unit tv field is required."]
            ] );

    }

    public function testStepFiveFailUnitTvBooleanValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-5/' . $application->id, [
                'unit_location'     => '123456789',
                'unit_type'         => '123456789',
                'unit_lease_length' => '12',
                'unit_car_parking'  => false,
                'unit_bike_parking' => false,
                'unit_tv'           => 'false',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "unit_tv" => ["The unit tv field must be true or false."]
            ] );

    }

    public function testStepFiveFailUnitStoreroomValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-5/' . $application->id, [
                'unit_location'     => '123456789',
                'unit_type'         => '123456789',
                'unit_lease_length' => '12',
                'unit_car_parking'  => false,
                'unit_bike_parking' => false,
                'unit_tv'           => false,
                'unit_storeroom'    => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "unit_storeroom" => ["The unit storeroom field is required."]
            ] );

    }

    public function testStepFiveFailUnitStoreroomBooleanValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-5/' . $application->id, [
                'unit_location'     => '123456789',
                'unit_type'         => '123456789',
                'unit_lease_length' => '12',
                'unit_car_parking'  => false,
                'unit_bike_parking' => false,
                'unit_tv'           => false,
                'unit_storeroom'    => 'false',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "unit_storeroom" => ["The unit storeroom field must be true or false."]
            ] );

    }
    */

    public function testStepFiveFailUnitOccupationDateValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-5/' . $application->id, [
                'unit_location'        => '123456789',
                'unit_type'            => '123456789',
                'unit_lease_length'    => '12',
                'unit_car_parking'     => FALSE,
                'unit_bike_parking'    => FALSE,
                'unit_tv'              => FALSE,
                'unit_storeroom'       => FALSE,
                'unit_occupation_date' => '',
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "unit_occupation_date" => ["The unit occupation date field is required."]
            ]);

    }

    public function testStepFivePassValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-5/' . $application->id, [
                'unit_location'        => '123456789',
                'unit_type'            => '123456789',
                'unit_lease_length'    => '12',
                'unit_car_parking'     => FALSE,
                'unit_bike_parking'    => FALSE,
                'unit_tv'              => FALSE,
                'unit_storeroom'       => FALSE,
                'unit_occupation_date' => '2016-01-21'
            ])
            ->assertResponseStatus(200);

    }

}
