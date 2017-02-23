<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class StepThreeApiTest extends Tests\TestCase
{
    use DatabaseMigrations;

    public function testStepThreeFailSelfEmployedRequiredValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-3/' . $application->id, [
                'selfemployed' => "",
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "selfemployed" => ["The selfemployed field is required."]
            ] );

    }

    public function testStepThreeFailSelfEmployedBooleanValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-3/' . $application->id, [
                'selfemployed' => "test",
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "selfemployed" => ["The selfemployed field must be true or false."]
            ] );

    }

    public function testStepThreeFailOccupationValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-3/' . $application->id, [
                'selfemployed' => true,
                'occupation'   => ""
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "occupation" => ["The occupation field is required."]
            ] );

    }

    public function testStepThreeFailCurrentMonthlyExpensesValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-3/' . $application->id, [
                'selfemployed'             => true,
                'occupation'               => "Do this",
                'current_monthly_expenses' => ""
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "current_monthly_expenses" => ["The current monthly expenses field is required."]
            ] );

    }

    public function testStepThreeFailEmployerCompanyValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-3/' . $application->id, [
                'selfemployed'             => false,
                'occupation'               => "Do this",
                'current_monthly_expenses' => "123",
                'employer_company'         => ""
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "employer_company" => ["The employer company field is required when selfemployed is ."]
            ] );

    }

    public function testStepThreeFailEmployerNameValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-3/' . $application->id, [
                'selfemployed'             => false,
                'occupation'               => "Do this",
                'current_monthly_expenses' => "123",
                'employer_company'         => "Company"
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "employer_name" => ["The employer name field is required when selfemployed is ."]
            ] );

    }

    public function testStepThreeFailEmployerPhoneWorkValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-3/' . $application->id, [
                'selfemployed'             => false,
                'occupation'               => "Do this",
                'current_monthly_expenses' => "123",
                'employer_company'         => "Company",
                'employer_name'            => 'Name Here'
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "employer_phone_work" => ["The employer phone work field is required when selfemployed is ."]
            ] );

    }

    public function testStepThreeFailEmployerEmailValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-3/' . $application->id, [
                'selfemployed'             => false,
                'occupation'               => "Do this",
                'current_monthly_expenses' => "123",
                'employer_company'         => "Company",
                'employer_name'            => 'Name Here',
                'employer_phone_work'      => '123'
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "employer_email" => ["The employer email field is required when selfemployed is ."]
            ] );

    }

    public function testStepThreeFailEmployerSalaryValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-3/' . $application->id, [
                'selfemployed'             => false,
                'occupation'               => "Do this",
                'current_monthly_expenses' => "123",
                'employer_company'         => "Company",
                'employer_name'            => 'Name Here',
                'employer_phone_work'      => '123',
                'employer_email'           => 'email@test.com'
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "employer_salary" => ["The employer salary field is required when selfemployed is ."]
            ] );

    }

    public function testStepThreePassValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-3/' . $application->id, [
                'selfemployed'             => false,
                'occupation'               => "Do this",
                'current_monthly_expenses' => "123",
                'employer_company'         => "Company",
                'employer_name'            => 'Name Here',
                'employer_phone_work'      => '123',
                'employer_email'           => 'email@test.com',
                'employer_salary'          => '12300'
            ] )
            ->assertResponseStatus( 200 );

    }

}
