<?php

use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table( 'users' )->insert( [
            'id'         => Uuid::generate()->string,
            'first_name' => "Catherine",
            'last_name'  => "Dohlhoff",
            'email'      => 'Catherine@swishproperties.co.za',
            'password'   => bcrypt( 'catherine20!7' ),
            'role'       => 'admin'
        ] );

    }
}
