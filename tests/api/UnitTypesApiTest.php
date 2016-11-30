<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class UnitTypesApiTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test a unit type submission fails on name
     *
     * @return void
     */
    public function testFailNameValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [])
            ->assertResponseStatus(422)
            ->seeJson([
                "name" => ["The name field is required."]
            ]);

    }

    /**
     * Test a unit type submission fails on location id
     *
     * @return void
     */
    public function testFailLocationIdValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name" => "Studio Unit"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "location_id" => ["The location id field is required."]
            ]);

    }

    /**
     * Test a unit type submission fails on location id exists
     *
     * @return void
     */
    public function testFailLocationIdExistsValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Studio Unit",
                "location_id" => "123345"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "location_id" => ["The selected location id is invalid."]
            ]);

    }

    /**
     * Test a unit type submission fails on cost
     *
     * @return void
     */
    public function testFailCostValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "cost" => ["The cost field is required."]
            ]);

    }

    /**
     * Test a unit type submission fails on cost
     *
     * @return void
     */
    public function testFailCostNumericValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789",
                "cost"        => 'qwe'
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "cost" => ["The cost must be a number."]
            ]);

    }

    /**
     * Test a unit type submission fails on wifi
     *
     * @return void
     */
    public function testFailWifiValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789",
                "cost"        => '123456'
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "wifi" => ["The wifi field is required."]
            ]);

    }

    /**
     * Test a unit type submission fails on wifi boolean
     *
     * @return void
     */
    public function testFailWifiBooleanValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789",
                "cost"        => '123456',
                "wifi"        => "qw12"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "wifi" => ["The wifi field must be true or false."]
            ]);

    }

    /**
     * Test a unit type submission fails on electricity
     *
     * @return void
     */
    public function testFailElectricityValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789",
                "cost"        => '123456',
                "wifi"        => TRUE
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "electricity" => ["The electricity field is required."]
            ]);

    }

    /**
     * Test a unit type submission fails on electricity boolean
     *
     * @return void
     */
    public function testFailElectricityBooleanValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789",
                "cost"        => '123456',
                "electricity" => "qw12"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "electricity" => ["The electricity field must be true or false."]
            ]);

    }

    /**
     * Test a unit type submission fails on dstv
     *
     * @return void
     */
    public function testFailDstvValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789",
                "cost"        => '123456',
                "wifi"        => TRUE,
                "electricity" => TRUE
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "dstv" => ["The dstv field is required."]
            ]);

    }

    /**
     * Test a unit type submission fails on dstv boolean
     *
     * @return void
     */
    public function testFailDstvBooleanValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789",
                "cost"        => '123456',
                "dstv"        => "qw12"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "dstv" => ["The dstv field must be true or false."]
            ]);

    }

    /**
     * Test a unit type submission fails on parking_car
     *
     * @return void
     */
    public function testFailParkingCarValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789",
                "cost"        => '123456',
                "wifi"        => TRUE,
                "electricity" => TRUE,
                "dstv"        => TRUE
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "parking_car" => ["The parking car field is required."]
            ]);

    }

    /**
     * Test a unit type submission fails on parking car boolean
     *
     * @return void
     */
    public function testFailParkingCarBooleanValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789",
                "cost"        => '123456',
                "parking_car" => "qw12"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "parking_car" => ["The parking car field must be true or false."]
            ]);

    }

    /**
     * Test a unit type submission fails on parking_bike
     *
     * @return void
     */
    public function testFailParkingBikeValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789",
                "cost"        => '123456',
                "wifi"        => TRUE,
                "electricity" => TRUE,
                "dstv"        => TRUE,
                "parking_car" => TRUE
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "parking_bike" => ["The parking bike field is required."]
            ]);

    }

    /**
     * Test a unit type submission fails on parking bike boolean
     *
     * @return void
     */
    public function testFailParkingBikeBooleanValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"         => "Castle Swish",
                "location_id"  => "123456789",
                "cost"         => '123456',
                "parking_bike" => "qw12"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "parking_bike" => ["The parking bike field must be true or false."]
            ]);

    }

    /**
     * Test a unit type submission fails on storeroom
     *
     * @return void
     */
    public function testFailStoreroomValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"         => "Castle Swish",
                "location_id"  => "123456789",
                "cost"         => '123456',
                "wifi"         => TRUE,
                "electricity"  => TRUE,
                "dstv"         => TRUE,
                "parking_car"  => TRUE,
                "parking_bike" => TRUE
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "storeroom" => ["The storeroom field is required."]
            ]);

    }

    /**
     * Test a unit type submission fails on storeroom boolean
     *
     * @return void
     */
    public function testFailStoreroomBooleanValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"        => "Castle Swish",
                "location_id" => "123456789",
                "cost"        => '123456',
                "storeroom"   => "qw12"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "storeroom" => ["The storeroom field must be true or false."]
            ]);

    }

    /**
     * Test a unit type submission passes
     *
     * @return void
     */
    public function testUnitTypePasses()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $location = factory(Portal\Location::class)->create();

        $this->actingAs($user)
            ->json('POST', '/unit-types', [
                "name"         => "Castle Swish",
                "location_id"  => $location->id,
                "cost"         => '123456',
                "wifi"         => FALSE,
                "electricity"  => FALSE,
                "dstv"         => FALSE,
                "parking_car"  => FALSE,
                "parking_bike" => FALSE,
                "storeroom"    => FALSE
            ])
            ->assertResponseStatus(200);

    }

}
