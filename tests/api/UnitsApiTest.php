<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class UnitsApiTest extends Tests\TestCase
{
    use DatabaseMigrations;

    /**
     * Test a unit submission fails on name
     *
     * @return void
     */
    public function testFailCodeValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/units', [])
            ->assertResponseStatus(422)
            ->seeJson([
                "code" => ["The code field is required."]
            ]);

    }

    /*
     * Test a unit submission fails on location id
     *
     * @return void
     */
    public function testFailLocationIdValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/units', [
                "code" => "MD123"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "location_id" => ["The location id field is required."]
            ]);

    }

    /**
     * Test a unit submission fails on location id exists
     *
     * @return void
     */
    public function testFailLocationIdExistsValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/units', [
                "code"        => "MD123",
                "location_id" => "123345"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "location_id" => ["The selected location id is invalid."]
            ]);

    }

    /*
     * Test a unit submission fails on location id
     *
     * @return void
     */
    public function testFailUnitTypeIdValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/units', [
                "code"        => "MD123",
                'location_id' => ""
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "type_id" => ["The type id field is required."]
            ]);

    }

    /**
     * Test a unit submission fails on location id exists
     *
     * @return void
     */
    public function testFailUnitTypeIdExistsValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/units', [
                "code"    => "MD123",
                "type_id" => "123345"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "type_id" => ["The selected type id is invalid."]
            ]);

    }

    /**
     * Test a unit submission fails on location id exists
     *
     * @return void
     */
    public function testFailUserIdExistsValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/units', [
                "code"    => "MD123",
                "user_id" => "123345"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "user_id" => ["The selected user id is invalid."]
            ]);

    }

    /**
     * Test a unit submission fails on location id exists
     *
     * @return void
     */
    public function testFailContractIdExistsValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/units', [
                "code"        => "MD123",
                "contract_id" => "123345"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "contract_id" => ["The selected contract id is invalid."]
            ]);

    }

    /**
     * Test a unit submission passes
     *
     * @return void
     */
    public function testUnitPasses()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $location = factory(Portal\Location::class)->create();

        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/units', [
                "code"        => "MD123",
                "location_id" => $location->id,
                "type_id" => $unitType->id
            ])
            ->assertResponseStatus(200);

    }

    /**
     * Test a unit submission fails on name
     *
     * @return void
     */
    public function testEditFailCodeValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('PUT', '/units/' . $unit->id, [])
            ->assertResponseStatus(422)
            ->seeJson([
                "code" => ["The code field is required."]
            ]);

    }

    /*
     * Test a unit submission fails on location id
     *
     * @return void
     */
    public function testEditFailLocationIdValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('PUT', '/units/' . $unit->id, [
                "code" => "MD123"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "location_id" => ["The location id field is required."]
            ]);

    }

    /**
     * Test a unit submission fails on location id exists
     *
     * @return void
     */
    public function testEditFailLocationIdExistsValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('PUT', '/units/' . $unit->id, [
                "code"        => "MD123",
                "location_id" => "123345"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "location_id" => ["The selected location id is invalid."]
            ]);

    }

    /*
     * Test a unit submission fails on location id
     *
     * @return void
     */
    public function testEditFailUnitTypeIdValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('PUT', '/units/' . $unit->id, [
                "code"        => "MD123",
                'location_id' => ""
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "type_id" => ["The type id field is required."]
            ]);

    }

    /**
     * Test a unit submission fails on location id exists
     *
     * @return void
     */
    public function testEditFailUnitTypeIdExistsValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('PUT', '/units/' . $unit->id, [
                "code"    => "MD123",
                "type_id" => "123345"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "type_id" => ["The selected type id is invalid."]
            ]);

    }

    /**
     * Test a unit submission fails on location id exists
     *
     * @return void
     */
    public function testEditFailUserIdExistsValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('PUT', '/units/' . $unit->id, [
                "code"    => "MD123",
                "user_id" => "123345"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "user_id" => ["The selected user id is invalid."]
            ]);

    }

    /**
     * Test a unit submission fails on location id exists
     *
     * @return void
     */
    public function testEditFailContractIdExistsValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('PUT', '/units/' . $unit->id, [
                "code"        => "MD123",
                "contract_id" => "123345"
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "contract_id" => ["The selected contract id is invalid."]
            ]);

    }

    /**
     * Test a unit submission passes
     *
     * @return void
     */
    public function testEditUnitPasses()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('PUT', '/units/' . $unit->id, [
                "code"        => "MD124",
                "location_id" => $location->id,
                "type_id"     => $unitType->id
            ])
            ->assertResponseStatus(200);

    }

}
