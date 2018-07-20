<?php

use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // Find a location 
        $location = \Portal\Location::all()->first();
        $unitType = \Portal\UnitType::all()->first();


        factory( Portal\Unit::class, 5 )->create( [
            'location_id' => $location->id,
            'type_id' => $unitType->id
        ] );


    }
}
