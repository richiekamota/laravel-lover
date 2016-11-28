<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class StepSixApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testStepSixFailValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-6/' . $application->id, [] )
            ->assertResponseStatus( 422 );

    }

    public function testStepSixFailJudgementsValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-6/' . $application->id, [
                'judgements' => ''
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "judgements" => ["The judgements field is required."]
            ] );

    }

    public function testStepSixFailJudgementsBooleanValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-6/' . $application->id, [
                'judgements' => 'test'
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "judgements" => ["The judgements field must be true or false."]
            ] );

    }

    public function testStepSixFailJudgementsDetailsValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-6/' . $application->id, [
                'judgements'         => true,
                'judgements_details' => ''
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "judgements_details" => ["The judgements details field is required."]
            ] );

    }

    public function testStepSixPassValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-6/' . $application->id, [
                'judgements'         => true,
                'judgements_details' => "Here are some details",
            ] )
            ->assertResponseStatus( 200 );

    }

}
