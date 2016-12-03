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
        'first_name'     => $faker->name,
        'last_name'      => $faker->name,
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
        'cost'        => $faker->numberBetween( 1, 12312313 )
    ];
} );

$factory->define( Portal\Contract::class, function ( Faker\Generator $faker ) {

    return [
        'id'          => Uuid::generate()->string,
        'start_date' => \Carbon\Carbon::today(),
        'end_date' => \Carbon\Carbon::today()->addMonths(11)
    ];
} );
