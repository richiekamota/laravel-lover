<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ItemsApiTest extends TestCase
{

    use DatabaseMigrations;

    /*
     * name
     * description
     * cost
     *
     * item_unit_type
     *
     */

    /*
     * Test an item submission fails on name validation
     */
    public function testFailNameValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/items', [] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "name" => [ "The name field is required." ]
            ] );

    }

    /*
     * Test an item submission fails on description validation
     */
    public function testFailDescriptionValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/items', [
                'name' => 'name'
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "description" => [ "The description field is required." ]
            ] );

    }

    /*
     * Test an item submission fails on cost validation
     */
    public function testFailCostValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/items', [
                'name'        => 'name',
                'description' => 'description'
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "cost" => [ "The cost field is required." ]
            ] );

    }

    /*
     * Test an item submission fails on cost numeric validation
     */
    public function testFailCostNumericValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/items', [
                'name'        => 'name',
                'description' => 'description',
                'cost'        => 'qwe'
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "cost" => [ "The cost must be a number." ]
            ] );

    }

    /*
     * Test an item submission fails on unit type validation
     */
    public function testFailUnitTypeValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/items', [
                'name'        => 'name',
                'description' => 'description',
                'cost'        => 123
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "unit_types" => [ "The unit types field is required." ]
            ] );

    }

    /*
     * Test an item submission fails on unit type validation
     */
    public function testPassValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );
        $location = factory( Portal\Location::class )->create();
        $unitTypes = factory( Portal\UnitType::class, 3 )->create( [
            'location_id' => $location->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/items', [
                'name'        => 'name',
                'description' => 'description',
                'cost'        => 123,
                'unit_types'   => [ $unitTypes[0]->id, $unitTypes[1]->id, $unitTypes[2]->id ]
            ] )
            ->assertResponseStatus( 200 )
            ->seeInDatabase( 'item_unit_type', [
                'unit_type_id' => $unitTypes[0]->id
            ] );

    }

    /*
     * Test an item submission fails on name validation
     */
    public function testFailEditNameValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );
        $item = factory( Portal\Item::class )->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/items/' .$item->id, [] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "name" => [ "The name field is required." ]
            ] );

    }

    /*
     * Test an item submission fails on description validation
     */
    public function testFailEditDescriptionValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );
        $item = factory( Portal\Item::class )->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/items/' . $item->id, [
                'name' => 'name'
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "description" => [ "The description field is required." ]
            ] );

    }

    /*
     * Test an item submission fails on cost validation
     */
    public function testFailEditCostValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );
        $item = factory( Portal\Item::class )->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/items/' . $item->id, [
                'name'        => 'name',
                'description' => 'description'
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "cost" => [ "The cost field is required." ]
            ] );

    }

    /*
     * Test an item submission fails on cost numeric validation
     */
    public function testFailEditCostNumericValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );
        $item = factory( Portal\Item::class )->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/items/' . $item->id, [
                'name'        => 'name',
                'description' => 'description',
                'cost'        => 'qwe'
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "cost" => [ "The cost must be a number." ]
            ] );

    }

    /*
     * Test an item submission fails on unit type validation
     */
    public function testFailEditUnitTypeValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );
        $item = factory( Portal\Item::class )->create();

        $this->actingAs( $user )
            ->json( 'PUT', '/items/' . $item->id, [
                'name'        => 'name',
                'description' => 'description',
                'cost'        => 123
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "unit_types" => [ "The unit types field is required." ]
            ] );

    }

    /*
     * Test an item submission fails on unit type validation
     */
    public function testEditPassValidation()
    {

        $user = factory( Portal\User::class )->create( [
            'role' => 'application'
        ] );
        $item = factory( Portal\Item::class )->create();
        $location = factory( Portal\Location::class )->create();
        $unitTypes = factory( Portal\UnitType::class, 3 )->create( [
            'location_id' => $location->id
        ] );

        $this->actingAs( $user )
            ->json( 'PUT', '/items/' . $item->id, [
                'name'        => 'name',
                'description' => 'description',
                'cost'        => 123,
                'unit_types'   => [ $unitTypes[0]->id, $unitTypes[1]->id, $unitTypes[2]->id ]
            ] )
            ->assertResponseStatus( 200 )
            ->seeInDatabase( 'item_unit_type', [
                'unit_type_id' => $unitTypes[0]->id
            ] );

    }

}
