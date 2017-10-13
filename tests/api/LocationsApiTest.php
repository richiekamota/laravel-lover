<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class LocationsApiTest extends Tests\TestCase
{
    use DatabaseMigrations;

    /**
     * Test a location submission fails on name
     *
     * @return void
     */
    public function testFailNameValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/locations', [] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "name" => ["The name field is required."]
            ] );

    }

    /**
     * Test a location submission fails on address
     *
     * @return void
     */
    public function testFailAddressValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/locations', [
                "name" => "Castle Swish"
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "address" => ["The address field is required."]
            ] );

    }

    /**
     * Test a location submission fails on address
     *
     * @return void
     */
    public function testFailCityValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/locations', [
                "name"    => "Castle Swish",
                "address" => "123 Boom Street"
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "city" => ["The city field is required."]
            ] );

    }

    /**
     * Test a location submission fails on region
     *
     * @return void
     */
    public function testFailRegionValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/locations', [
                "name"    => "Castle Swish",
                "address" => "123 Boom Street",
                "city"    => "Boom Town"
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "region" => ["The region field is required."]
            ] );

    }

    /**
     * Test a location submission passes validaiton
     *
     * @return void
     */
    public function testPassesValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/locations', [
                "name"    => "Castle Swish",
                "address" => "123 Boom Street",
                "city"    => "Boom Town",
                "region"  => "KaBang"
            ] )
            ->assertResponseStatus( 200 );

    }

    /**
     * Test a location submission passes validation but
     * fails the authorisation policy
     *
     * @return void
     */
    public function testFailsAuthorisationPolicy()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'tenant'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/locations', [
                "name"    => "Castle Swish",
                "address" => "123 Boom Street",
                "city"    => "Boom Town",
                "region"  => "KaBang"
            ] )
            ->assertResponseStatus( 403 );

    }

    /**
     * Test a location submission returns the right data
     *
     * @return void
     */
    public function testReturnsCorrectData()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/locations', [
                "name"    => "Castle Swish",
                "address" => "123 Boom Street",
                "city"    => "Boom Town",
                "region"  => "KaBang"
            ] )
            ->assertResponseStatus( 200 )
            ->seeJsonStructure([
                'message',
                'data' => [
                    'id', 'name', 'address'
                ]
            ]);

    }

    /**
     * Test a location submission fails on name
     *
     * @return void
     */
    public function testEditFailNameValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $location = factory(Portal\Location::class)->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/locations/' . $location->id, [
                'name' => ''
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "name" => ["The name field is required."]
            ] );

    }

    /**
     * Test a location submission fails on address
     *
     * @return void
     */
    public function testEditFailAddressValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $location = factory(Portal\Location::class)->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/locations/' . $location->id, [
                "name" => "Castle Swish"
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "address" => ["The address field is required."]
            ] );

    }

    /**
     * Test a location submission fails on address
     *
     * @return void
     */
    public function testEditFailCityValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $location = factory(Portal\Location::class)->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/locations/' . $location->id, [
                "name"    => "Castle Swish",
                "address" => "123 Boom Street"
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "city" => ["The city field is required."]
            ] );

    }

    /**
     * Test a location submission fails on region
     *
     * @return void
     */
    public function testEditFailRegionValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $location = factory(Portal\Location::class)->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/locations/' . $location->id, [
                "name"    => "Castle Swish",
                "address" => "123 Boom Street",
                "city"    => "Boom Town"
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "region" => ["The region field is required."]
            ] );

    }

    /**
     * Test a location submission passes validaiton
     *
     * @return void
     */
    public function testEditPassesValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $location = factory(Portal\Location::class)->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/locations/' . $location->id, [
                "name"    => "Castle Swish",
                "address" => "123 Boom Street",
                "city"    => "Boom Town",
                "region"  => "KaBang"
            ] )
            ->assertResponseStatus( 200 );

    }

    /**
     * Test a location submission passes validation but
     * fails the authorisation policy
     *
     * @return void
     */
    public function testEditFailsAuthorisationPolicy()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'tenant'
        ] );

        $location = factory(Portal\Location::class)->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/locations/' . $location->id, [
                "name"    => "Castle Swish",
                "address" => "123 Boom Street",
                "city"    => "Boom Town",
                "region"  => "KaBang"
            ] )
            ->assertResponseStatus( 403 );

    }

    /**
     * Test a location submission returns the right data
     *
     * @return void
     */
    public function testEditReturnsCorrectData()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $location = factory(Portal\Location::class)->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/locations/' . $location->id, [
                "name"    => "Castle Swish",
                "address" => "123 Boom Street",
                "city"    => "Boom Town",
                "region"  => "KaBang"
            ] )
            ->assertResponseStatus( 200 )
            ->seeJsonStructure([
                'message',
                'data' => [
                    'id', 'name', 'address'
                ]
            ]);

    }


}
