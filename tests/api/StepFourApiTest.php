<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class StepFourApiTest extends Tests\TestCase
{
    use DatabaseMigrations;

    public function testStepFourFailValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [] )
            ->assertResponseStatus( 422 );

    }

    public function testStepFourFailResidentFirstNameValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name' => ''
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_first_name" => ["The resident first name field is required."]
            ] );

    }

    public function testStepFourFailResidentLastNameValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name' => 'Phil',
                'resident_last_name'  => ''
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_last_name" => ["The resident last name field is required."]
            ] );

    }

    public function testStepFourFailResidentSAIdNumberValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'   => 'Phil',
                'resident_last_name'    => 'Benoit',
                'resident_sa_id_number' => ''
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_sa_id_number" => ["The resident sa id number field is required when resident passport number is ."]
            ] );

    }

    public function testStepFourFailResidentDOBValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'   => 'Phil',
                'resident_last_name'    => 'Benoit',
                'resident_sa_id_number' => '123456789',
                'resident_dob'          => '',

            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_dob" => ["The resident dob field is required."]
            ] );

    }

    public function testStepFourFailResidentNationalityValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'   => 'Phil',
                'resident_last_name'    => 'Benoit',
                'resident_sa_id_number' => '123456789',
                'resident_dob'          => '1982-08-06',
                'resident_nationality'  => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_nationality" => ["The resident nationality field is required."]
            ] );

    }

    public function testStepFourFailResidentPhoneNumberValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'   => 'Phil',
                'resident_last_name'    => 'Benoit',
                'resident_sa_id_number' => '123456789',
                'resident_dob'          => '1982-08-06',
                'resident_nationality'  => 'Kiwi',
                'resident_phone_mobile' => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_phone_mobile" => ["The resident phone mobile field is required."]
            ] );

    }

    public function testStepFourFailResidentEmailValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'   => 'Phil',
                'resident_last_name'    => 'Benoit',
                'resident_sa_id_number' => '123456789',
                'resident_dob'          => '1982-08-06',
                'resident_nationality'  => 'Kiwi',
                'resident_phone_mobile' => '123456789',
                'resident_email'        => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_email" => ["The resident email field is required."]
            ] );

    }

    public function testStepFourFailResidentCurrentAddressValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'      => 'Phil',
                'resident_last_name'       => 'Benoit',
                'resident_sa_id_number'    => '123456789',
                'resident_dob'             => '1982-08-06',
                'resident_nationality'     => 'Kiwi',
                'resident_phone_mobile'    => '123456789',
                'resident_email'           => 'phil@test.com',
                'resident_current_address' => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_current_address" => ["The resident current address field is required."]
            ] );

    }

    public function testStepFourFailResidentLandlordValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'      => 'Phil',
                'resident_last_name'       => 'Benoit',
                'resident_sa_id_number'    => '123456789',
                'resident_dob'             => '1982-08-06',
                'resident_nationality'     => 'Kiwi',
                'resident_phone_mobile'    => '123456789',
                'resident_email'           => 'phil@test.com',
                'resident_current_address' => '14 Some Street',
                'resident_landlord'        => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_landlord" => ["The resident landlord field is required."]
            ] );

    }

    public function testStepFourFailResidentLandlordPhoneWorkValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'          => 'Phil',
                'resident_last_name'           => 'Benoit',
                'resident_sa_id_number'        => '123456789',
                'resident_dob'                 => '1982-08-06',
                'resident_nationality'         => 'Kiwi',
                'resident_phone_mobile'        => '123456789',
                'resident_email'               => 'phil@test.com',
                'resident_current_address'     => '14 Some Street',
                'resident_landlord'            => 'Meg Benoit',
                'resident_landlord_phone_work' => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_landlord_phone_work" => ["The resident landlord phone work field is required."]
            ] );

    }

    public function testStepFourFailResidentLandlordPhoneMobileValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'            => 'Phil',
                'resident_last_name'             => 'Benoit',
                'resident_sa_id_number'          => '123456789',
                'resident_dob'                   => '1982-08-06',
                'resident_nationality'           => 'Kiwi',
                'resident_phone_mobile'          => '123456789',
                'resident_email'                 => 'phil@test.com',
                'resident_current_address'       => '14 Some Street',
                'resident_landlord'              => 'Meg Benoit',
                'resident_landlord_phone_work'   => '123456789',
                'resident_landlord_phone_mobile' => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_landlord_phone_mobile" => ["The resident landlord phone mobile field is required."]
            ] );

    }

    public function testStepFourFailResidentStudyLocationValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'            => 'Phil',
                'resident_last_name'             => 'Benoit',
                'resident_sa_id_number'          => '123456789',
                'resident_dob'                   => '1982-08-06',
                'resident_nationality'           => 'Kiwi',
                'resident_phone_mobile'          => '123456789',
                'resident_email'                 => 'phil@test.com',
                'resident_current_address'       => '14 Some Street',
                'resident_landlord'              => 'Meg Benoit',
                'resident_landlord_phone_work'   => '123456789',
                'resident_landlord_phone_mobile' => '123456789',
                'resident_study_location'        => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_study_location" => ["The resident study location field is required."]
            ] );

    }

    public function testStepFourFailResidentStudyYearValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'            => 'Phil',
                'resident_last_name'             => 'Benoit',
                'resident_sa_id_number'          => '123456789',
                'resident_dob'                   => '1982-08-06',
                'resident_nationality'           => 'Kiwi',
                'resident_phone_mobile'          => '123456789',
                'resident_email'                 => 'phil@test.com',
                'resident_current_address'       => '14 Some Street',
                'resident_landlord'              => 'Meg Benoit',
                'resident_landlord_phone_work'   => '123456789',
                'resident_landlord_phone_mobile' => '123456789',
                'resident_study_location'        => 'UCT',
                'resident_study_year'            => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_study_year" => ["The resident study year field is required."]
            ] );

    }

    public function testStepFourFailResidentGenderValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'            => 'Phil',
                'resident_last_name'             => 'Benoit',
                'resident_sa_id_number'          => '123456789',
                'resident_dob'                   => '1982-08-06',
                'resident_nationality'           => 'Kiwi',
                'resident_phone_mobile'          => '123456789',
                'resident_email'                 => 'phil@test.com',
                'resident_current_address'       => '14 Some Street',
                'resident_landlord'              => 'Meg Benoit',
                'resident_landlord_phone_work'   => '123456789',
                'resident_landlord_phone_mobile' => '123456789',
                'resident_study_location'        => 'UCT',
                'resident_study_year'            => '3',
                'resident_gender'                => '',
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "resident_gender" => ["The resident gender field is required."]
            ] );

    }

    public function testStepFourPassValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-4/' . $application->id, [
                'resident_first_name'            => 'Phil',
                'resident_last_name'             => 'Benoit',
                'resident_sa_id_number'          => '123456789',
                'resident_dob'                   => '1982-08-06',
                'resident_nationality'           => 'Kiwi',
                'resident_phone_mobile'          => '123456789',
                'resident_email'                 => 'phil@test.com',
                'resident_current_address'       => '14 Some Street',
                'resident_landlord'              => 'Meg Benoit',
                'resident_landlord_phone_work'   => '123456789',
                'resident_landlord_phone_mobile' => '123456789',
                'resident_study_location'        => 'UCT',
                'resident_study_year'            => '3',
                'resident_gender'                => 'Male',
            ] )
            ->assertResponseStatus( 200 );

    }

}
