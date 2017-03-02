<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class StepEightApiTest extends Tests\TestCase
{
    use DatabaseMigrations;

    public function testStepEightFailValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-8/' . $application->id, [] )
            ->assertResponseStatus( 422 );

    }

    public function testStepEightFailConfirmValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-8/' . $application->id, [
                'confirm' => ''
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "confirm" => ["The confirm field is required."]
            ] );

    }

    // Confirmation time validation not required. Automatically saved to system.
    /*
    public function testStepEightFailConfirmTimeValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-8/' . $application->id, [
                'confirm' => true,
                'confirm_time' => ''
            ] )
            ->assertResponseStatus( 422 )
            ->seeJson( [
                "confirm_time" => ["The confirm time field is required."]
            ] );

    }*/

    public function testStepEightPassValidation()
    {

        $user = factory( Portal\User::class )->create();

        $application = factory( Portal\Application::class )->create( [
            'user_id' => $user->id,
            'sa_id_number'                   => '123456789',
            'dob'                            => '1982-06-08',
            'nationality'                    => 'Kiwi',
            'current_address'                => '14 Valentino Drive',
            'marital_status'                 => 'Single',
            'current_property_owner'         => true,
            'selfemployed'                   => true,
            'occupation'                     => 'Web Dev',
            'current_monthly_expenses'       => '12000',
            'resident_first_name'            => 'Phil',
            'resident_last_name'             => 'Benoit',
            'resident_sa_id_number'          => '123456789',
            'resident_dob'                   => '1982-06-08',
            'resident_nationality'           => 'Kiwi',
            'resident_phone_mobile'          => '12346789',
            'resident_email'                 => 'phil@test.com',
            'resident_current_address'       => '14 Valentino',
            'resident_landlord'              => 'Meg Benoit',
            'resident_landlord_phone_work'   => '123456789',
            'resident_landlord_phone_mobile' => '123456789',
            'resident_study_location'        => '12456789',
            'resident_study_year'            => '2',
            'resident_gender'                => 'Male',
            'unit_location'                  => '123456789',
            'unit_type'                      => '123456789',
            'unit_lease_length'              => '12',
            'unit_car_parking'               => false,
            'unit_bike_parking'              => false,
            'unit_tv'                        => false,
            'unit_storeroom'                 => false,
            'unit_occupation_date'           => '2016-01-01',
            'judgements'                     => false,
            'resident_id'                    => '123456789',
            'resident_study_permit'          => '123456789',
            'resident_acceptance'            => '123456789',
            'resident_financial_aid'         => '123456789',
            'leaseholder_id'                 => '123456789',
            'leaseholder_address_proof'      => '123456789',
            'leaseholder_payslip'            => '123456789'
        ] );

        $this->actingAs( $user )
            ->json( 'POST', '/step-8/' . $application->id, [
                'confirm'      => true,
                'confirm_time' => '2016-01-21'
            ] )
            ->assertResponseStatus( 200 );

    }

}
