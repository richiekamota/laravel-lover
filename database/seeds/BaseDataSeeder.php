<?php

use Illuminate\Database\Seeder;
use Portal\User;

class BaseDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $locationId = Uuid::generate()->string;

        DB::table( 'locations' )->insert([
            'id'      => $locationId,
            'name'    => "Wynburg",
            'address' => "No 9, Lower Church Street",
            'city'    => 'Cape Town',
            'region'  => 'Western Provence',
            'code'    => '7824'
        ]);

        DB::table( 'unit_types' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => "Studio",
            'description'  => "Open plan studio furnished with single bed base & single mattress, built in  desk(no chair ), wardrobe, kitchenette with prep bowl & induction hot plate, mini-refrigerator, bathroom pod (shower, basin and toilet) and WI FI- uncapped internet data per month(Fibre lines installed). Water & Electricity included.",
            'location_id'  => $locationId,
            'cost'         => '3850',
            'wifi'         => true,
            'electricity'  => true,
            'dstv'         => false,
            'parking_car'  => false,
            'parking_bike' => false,
            'storeroom'    => false,
        ]);

        DB::table( 'unit_types' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => "Classic Studio",
            'description'  => "Open plan studio furnished with 3/4 bed base & 3/4 mattress, built in  desk(no chair ), wardrobe, kitchenette with prep bowl & induction hot plate, mini-refrigerator, bathroom pod (shower, basin and toilet) and WI FI- uncapped internet data per month(Fibre lines installed). Water & Electricity included.",
            'location_id'  => $locationId,
            'cost'         => '4250',
            'wifi'         => true,
            'electricity'  => true,
            'dstv'         => false,
            'parking_car'  => false,
            'parking_bike' => false,
            'storeroom'    => false,
        ]);

        DB::table( 'unit_types' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => "Premium Studio",
            'description'  => "Open plan studio furnished with double bed base & double mattress, built in  desk(no chair ), wardrobe, kitchenette with prep bowl & induction hot plate, mini-refrigerator, bathroom pod (shower, basin and toilet),  10 channel DSTV bouquet and WI FI- uncapped internet data per month(Fibre lines installed). Water & Electricity included.",
            'location_id'  => $locationId,
            'cost'         => '4750',
            'wifi'         => true,
            'electricity'  => true,
            'dstv'         => true,
            'parking_car'  => false,
            'parking_bike' => false,
            'storeroom'    => false,
        ]);

        DB::table( 'unit_types' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => "Deluxe Studio",
            'description'  => "Open plan studio furnished with double bed base & double mattress, built in  desk(no chair ), wardrobe, kitchenette with prep bowl & induction hot plate, mini-refrigerator, conventional bathroom (shower, basin and toilet),  10 channel DSTV bouquet and WI FI- uncapped internet data per month(Fibre lines installed). Water & Electricity included.",
            'location_id'  => $locationId,
            'cost'         => '5250',
            'wifi'         => true,
            'electricity'  => true,
            'dstv'         => true,
            'parking_car'  => false,
            'parking_bike' => false,
            'storeroom'    => false,
        ]);

        DB::table( 'unit_types' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => "Twin Studio",
            'description'  => "Open plan studio furnished with 2 single bed bases& 2 double mattresses, 2 built in desks(no chair ), 2 wardrobes, kitchenette with prep bowl & induction hot plate, mini-refrigerator, conventional bathroom (shower, basin and toilet and WI FI- uncapped internet data per month(Fibre lines installed). Water & Electricity included.",
            'location_id'  => $locationId,
            'cost'         => '5350',
            'wifi'         => true,
            'electricity'  => true,
            'dstv'         => false,
            'parking_car'  => false,
            'parking_bike' => false,
            'storeroom'    => false,
        ]);

        DB::table( 'unit_types' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => "2 Bedroom Unit",
            'description'  => "2 Separate bedrooms with 2 ¾ beds & 2 ¾  mattresses, 2 built in desks(no chair ), 2 wardrobes, kitchenette with prep bowl & induction hot plate, mini-refrigerator, conventional bathroom (shower, basin and toilet.) 10 channel DSTV bouquet and WI FI- uncapped internet data per month(Fibre lines installed). Water & Electricity included.",
            'location_id'  => $locationId,
            'cost'         => '6850',
            'wifi'         => true,
            'electricity'  => true,
            'dstv'         => true,
            'parking_car'  => false,
            'parking_bike' => false,
            'storeroom'    => false,
        ]);

        /*factory( Portal\Location::class, 3 )->create( [
            'id' => Uuid::generate()->string
        ] )->each( function ( $l ) {

            factory( Portal\UnitType::class, 2 )->create( [
                'location_id' => $l->id
            ] )->each( function ( $u ) {

                factory( Portal\Unit::class, 10 )->create( [
                    'location_id' => $u->location_id,
                    'type_id'     => $u->id
                ] );
            } );
        } );*/


        $user = factory( Portal\User::class )->create( [
            'role' => 'tenant'
        ] );

        $residentId = factory(Portal\Document::class)->create( [
            'user_id' => $user->id,
            'type' => 'resident_id'
        ] );

        $location = \Portal\Location::all()->first();

        $craig = User::whereEmail('craig@swishproperties.co.za')->first();

        factory( Portal\Application::class, 5 )->states( 'forApproval' )->create( [
            'user_id' => $craig->id,
            'email' => 'craig@swishproperties.co.za',
            'resident_id' => $residentId->id,
            'unit_location' => $location->id,
            'unit_type' => $location->unitTypes->first()->id
        ] );

        $unit = $location->unitTypes->first();

        // RENTAL FEES

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Studio Unit - Rental Fee',
            'description'  => 'Studio Unit - Rental Fee',
            'cost'         => 3850,
            'payment_type' => 'Monthly',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Classic Studio Unit - Rental Fee',
            'description'  => 'Classic Studio Unit - Rental Fee',
            'cost'         => 4250,
            'payment_type' => 'Monthly',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Premium Studio Unit - Rental Fee',
            'description'  => 'Premium Studio Unit - Rental Fee',
            'cost'         => 4750,
            'payment_type' => 'Monthly',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Deluxe Studio Unit - Rental Fee',
            'description'  => 'Deluxe Studio Unit - Rental Fee',
            'cost'         => 5250,
            'payment_type' => 'Monthly',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Twin Studio Unit - Rental Fee',
            'description'  => 'Twin Studio Unit - Rental Fee',
            'cost'         => 5350,
            'payment_type' => 'Monthly',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => '2 Bedroom Unit - Rental Fee',
            'description'  => '2 Bedroom Unit - Rental Fee',
            'cost'         => 6850,
            'payment_type' => 'Monthly',
            'created_at' => \Carbon\Carbon::today()
        ]);

        // SECURITY DEPOSITS

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Studio Unit - Security Deposit',
            'description'  => 'Studio Unit - Security Deposit',
            'cost'         => 3850,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Classic Studio Unit - Security Deposit',
            'description'  => 'Classic Studio Unit - Security Deposit',
            'cost'         => 4250,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Premium Studio Unit - Security Deposit',
            'description'  => 'Premium Studio Unit - Security Deposit',
            'cost'         => 4750,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Deluxe Studio Unit - Security Deposit',
            'description'  => 'Deluxe Studio Unit - Security Deposit',
            'cost'         => 5250,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Twin Studio Unit - Security Deposit',
            'description'  => 'Twin Studio Unit - Security Deposit',
            'cost'         => 5350,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => '2 Bedroom Unit - Security Deposit',
            'description'  => '2 Bedroom Unit - Security Deposit',
            'cost'         => 6850,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        // DAMAGE DEPOSIT
        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Studio Unit - Damage Deposit',
            'description'  => 'Studio Unit - Damage Deposit',
            'cost'         => 3850,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Classic Studio Unit - Damage Deposit',
            'description'  => 'Classic Studio Unit - Damage Deposit',
            'cost'         => 4250,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Premium Studio Unit - Damage Deposit',
            'description'  => 'Premium Studio Unit - Damage Deposit',
            'cost'         => 4750,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Deluxe Studio Unit - Damage Deposit',
            'description'  => 'Deluxe Studio Unit - Damage Deposit',
            'cost'         => 5250,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Twin Studio Unit - Damage Deposit',
            'description'  => 'Twin Studio Unit - Damage Deposit',
            'cost'         => 5350,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => '2 Bedroom Unit - Damage Deposit',
            'description'  => '2 Bedroom Unit - Damage Deposit',
            'cost'         => 6850,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);



        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Parking Bay',
            'description'  => 'Parking Bay',
            'cost'         => 300,
            'payment_type' => 'Monthly',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Motorcycle Parking Bay',
            'description'  => 'Motorcycle Parking Bay',
            'cost'         => 150,
            'payment_type' => 'Monthly',
            'created_at' => \Carbon\Carbon::today()
        ]);


        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'DSTV',
            'description'  => '10 Channel DSTV bouquet',
            'cost'         => 150,
            'payment_type' => 'Monthly',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'DSTV - Activation Fee',
            'description'  => 'Once-off DSTV activation fee',
            'cost'         => 750,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Storeroom',
            'description'  => 'Storeroom',
            'cost'         => 400,
            'payment_type' => 'Monthly',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Access Card Fee',
            'description'  => 'Access Card Fee',
            'cost'         => 400,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Mattress Protector Fee - 1x Single',
            'description'  => 'Mattress Protector Fee - 1x Single',
            'cost'         => 235,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Mattress Protector Fee - 1x 3/4',
            'description'  => 'Mattress Protector Fee - 1x 3/4',
            'cost'         => 255,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);


        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Mattress Protector Fee - 1x Double',
            'description'  => 'Mattress Protector Fee - 1x Double',
            'cost'         => 290,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Mattress Protector Fee - 2x Single',
            'description'  => 'Mattress Protector Fee - 2x Single',
            'cost'         => 470,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Mattress Protector Fee - 2x 3/4',
            'description'  => 'Mattress Protector Fee - 2x 3/4',
            'cost'         => 580,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);

        DB::table( 'items' )->insert([
            'id'           => Uuid::generate()->string,
            'name'         => 'Lease & Credit Check Fee',
            'description'  => 'Lease & Credit Check Fee',
            'cost'         => 550,
            'payment_type' => 'Once-off',
            'created_at' => \Carbon\Carbon::today()
        ]);
    }
}
