<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define( Portal\User::class, function ( Faker\Generator $faker ) {

    static $password;

    return [
        'first_name'     => $faker->firstName,
        'last_name'      => $faker->lastName,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt( 'secret' ),
        'remember_token' => str_random( 10 ),
    ];
} );

$factory->define( Portal\Application::class, function ( Faker\Generator $faker ) {

    return [
        'status' => 'draft',
        'step'   => '1'
    ];
} );

$factory->state( Portal\Application::class, 'forApproval', function ( Faker\Generator $faker ) {

    return [
        'status'                         => 'open',
        'step'                           => '8',
        'first_name'                     => $faker->firstName,
        'last_name'                      => $faker->lastName,
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
    ];
} );

$factory->define( Portal\Location::class, function ( Faker\Generator $faker ) {

    return [
        'name'    => $faker->word,
        'address' => $faker->address,
        'city'    => $faker->city,
        'region'  => $faker->city,
    ];
} );

$factory->define( Portal\UnitType::class, function ( Faker\Generator $faker ) {

    return [
        'id'           => Uuid::generate()->string,
        'name'         => $faker->word,
        'description'  => $faker->realText( $maxNbChars = 200, $indexSize = 2 ),
        'cost'         => $faker->numberBetween( 1000, 6000 ),
        'wifi'         => $faker->boolean( 50 ),
        'electricity'  => $faker->boolean( 50 ),
        'dstv'         => $faker->boolean( 50 ),
        'parking_car'  => $faker->boolean( 50 ),
        'parking_bike' => $faker->boolean( 50 ),
        'storeroom'    => $faker->boolean( 50 ),
    ];
} );

$factory->define( Portal\Unit::class, function ( Faker\Generator $faker ) {

    return [
        'id'   => Uuid::generate()->string,
        'code' => 'MD' . $faker->randomNumber( 3 )
    ];
} );

$factory->define( Portal\Item::class, function ( Faker\Generator $faker ) {

    return [
        'id'          => Uuid::generate()->string,
        'name'        => $faker->word,
        'description' => $faker->word,
        'cost'        => $faker->numberBetween( 1, 1234 )
    ];
} );

$factory->define( Portal\Contract::class, function ( Faker\Generator $faker ) {

    return [
        'id'         => Uuid::generate()->string,
        'start_date' => \Carbon\Carbon::today(),
        'end_date'   => \Carbon\Carbon::today()->addMonths( 11 )
    ];
} );

$factory->define( Portal\Document::class, function ( Faker\Generator $faker ) {

    return [
        'id'        => Uuid::generate()->string,
        'file_name' => '460c117919ba8720624c6592acaacb88',
        'location'  => '460c117919ba8720624c6592acaacb88.png'
    ];

} );