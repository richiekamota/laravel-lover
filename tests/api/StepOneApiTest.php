<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class StepOneApiTest extends Tests\TestCase
{
    use DatabaseMigrations;

    /**
     * Test a user can submit the first part of the application form
     *
     * @return void
     */
    public function testStepOneFailValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-1/' . $application->id, [] )
            ->assertResponseStatus( 422 );

    }

    public function testStepOneFailDobValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-1/' . $application->id, [
                'sa_id_number'    => '123456',
                'nationality'     => 'British',
                'current_address' => '14 Valentino',
                'marital_status'  => 'Married',
                'married_type'    => 'ANC',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "dob" => ["The dob field is required."]
            ] );

    }

    public function testStepOneFailNationalityValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-1/' . $application->id, [
                'sa_id_number'    => '123456',
                'dob'             => '6/8/1982',
                'current_address' => '14 Valentino',
                'marital_status'  => 'Married',
                'married_type'    => 'ANC',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                'nationality' => ["The nationality field is required."],
            ] );

    }

    public function testStepOneFailMaritalStatusValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-1/' . $application->id, [
                'sa_id_number'    => '123456',
                'dob'             => '6/8/1982',
                'nationality'     => 'British',
                'current_address' => '14 Valentino',
                'married_type'    => 'ANC',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "marital_status" => ["The marital status field is required."]
            ] );

    }

    public function testStepOneFailMarriedTypeValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-1/' . $application->id, [
                'sa_id_number'    => '123456',
                'dob'             => '6/8/1982',
                'nationality'     => 'British',
                'current_address' => '14 Valentino',
                'marital_status'  => 'Married'
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "married_type" => ["The married type field is required when marital status is Married."]
            ] );

    }

    public function testStepOnePassValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-1/' . $application->id, [
                'first_name'      => 'John',
                'last_name'       => 'Doe',
                'sa_id_number'    => '123456',
                'dob'             => '1970/01/01',
                'nationality'     => 'South African',
                'current_address' => 'street name 123',
                'marital_status'  => 'Married',
                'married_type'    => 'ANC'
            ] )
            ->assertResponseStatus( 200 );

    }

}
