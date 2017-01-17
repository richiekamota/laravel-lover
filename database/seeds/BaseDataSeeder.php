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

        DB::table( 'locations' )->insert( [
            'id'      => $locationId,
            'name'    => "Wynburg",
            'address' => "123 Some Street",
            'city'    => 'Cape Town',
            'region'  => 'Western Provence',
            'code'    => 'loc123'
        ] );

        DB::table( 'unit_types' )->insert( [
            'id'           => Uuid::generate()->string,
            'name'         => "Wynburg",
            'description'  => "Our Studios are ideal for students wanting to keep costs their down without sacrificing comfort whilst enjoying their fabulous Cape Town student accommodation. The  Studio features: Open plan studio furnished with a single bed base, mattress and mattress protector. Built-in work desk (chair is not provided), clothes wardrobe and a small kitchenette with a mini-refrigerator and induction hotplate. Innovative bathroom pod with a shower, basin and toilet. Uncapped internet data per month",
            'location_id'  => $locationId,
            'cost'         => '3456',
            'wifi'         => false,
            'electricity'  => false,
            'dstv'         => false,
            'parking_car'  => false,
            'parking_bike' => false,
            'storeroom'    => false,
        ] );


        factory( Portal\Location::class, 3 )->create( [
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
        } );

        $user = factory( Portal\User::class )->create( [
            'role' => 'tenant'
        ] );

        $residentId = factory(Portal\Document::class)->create( [
            'user_id' => $user->id,
            'document_type' => 'resident_id'
        ] );

        $location = \Portal\Location::all()->first();

        $craig = User::whereEmail('craig@swishproperties.co.za')->first();

        factory( Portal\Application::class, 5 )->states( 'forApproval' )->create( [
            'user_id' => $craig->id,
            'resident_id' => $residentId->id,
            'unit_location' => $location->id,
            'unit_type' => $location->unitTypes->first()->id
        ] );

        $unit = $location->unitTypes->first();

        factory( Portal\Item::class, 5 )->create( [
            'id' => Uuid::generate()->string
        ] )->each( function ( $l ) use ($unit) {
            DB::table( 'item_unit_type' )->insert( [
                'item_id' => $l->id,
                'unit_type_id' => $unit->id
            ]);
        });

    }
}
