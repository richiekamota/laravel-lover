<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApplicationApiTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test a user can submit the first part of the application form
     *
     * @return void
     */
    public function testStepOneFailValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-1/' . $application->id, [])
            ->assertResponseStatus(422);

    }

    public function testStepOneFailDobValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-1/' . $application->id, [
                'sa_id_number'    => '123456',
                'nationality'     => 'British',
                'current_address' => '14 Valentino',
                'marital_status'  => 'Married',
                'married_type'    => 'ANC',
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "dob" => ["The dob field is required."]
            ]);

    }

    public function testStepOneFailNationalityValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-1/' . $application->id, [
                'sa_id_number'    => '123456',
                'dob'             => '6/8/1982',
                'current_address' => '14 Valentino',
                'marital_status'  => 'Married',
                'married_type'    => 'ANC',
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                'nationality' => ["The nationality field is required."],
            ]);

    }

    public function testStepOneFailMaritalStatusValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-1/' . $application->id, [
                'sa_id_number'    => '123456',
                'dob'             => '6/8/1982',
                'nationality'     => 'British',
                'current_address' => '14 Valentino',
                'married_type'    => 'ANC',
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "marital_status" => ["The marital status field is required."]
            ]);

    }

    public function testStepOneFailMarriedTypeValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-1/' . $application->id, [
                'sa_id_number'    => '123456',
                'dob'             => '6/8/1982',
                'nationality'     => 'British',
                'current_address' => '14 Valentino',
                'marital_status'  => 'Married'
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "married_type" => ["The married type field is required when marital status is Married."]
            ]);

    }

    public function testStepOnePassValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-1/' . $application->id, [
                'sa_id_number'    => '123456',
                'dob'             => '1982-08-06',
                'nationality'     => 'British',
                'current_address' => '14 Valentino',
                'marital_status'  => 'Single'
            ])
            ->assertResponseStatus(200);

    }

    public function testStepTwoFailValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-2/' . $application->id, [])
            ->assertResponseStatus(422);

    }

    public function testStepTwoFailRentalValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-2/' . $application->id, [
                'current_property_owner' => 'false',
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "rental_amount" => ["The rental amount field is required when current property owner is false."]
            ]);

    }

    public function testStepTwoFailRentalTimeValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-2/' . $application->id, [
                'current_property_owner' => 'false',
                'rental_time'            => '06'
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "rental_time" => ["The selected rental time is invalid."]
            ]);

    }

    public function testStepTwoFailRentalAmountValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-2/' . $application->id, [
                'current_property_owner' => 'false',
                'rental_time'            => '12',
                'rental_amount'          => 'abc'
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "rental_amount" => ["The rental amount must be an integer."]
            ]);

    }

    public function testStepTwoPassValidation()
    {

        $user = factory(Portal\User::class)->create();

        $application = factory(Portal\Application::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/step-2/' . $application->id, [
                'current_property_owner' => false,
                'rental_time'            => '12',
                'rental_amount'          => '123',
                'rental_name'            => 'Phil Benoit',
                'rental_phone_home'      => '123456789',
                'rental_phone_mobile'    => '123456789',
            ])
            ->assertResponseStatus(200);

    }
}
