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
            'first_name' => "Phil",
            'last_name'  => "Benoit",
            'email'      => 'phil@incendiaryblue.com',
            'password'   => bcrypt( 'qwerty123' ),
            'role'       => 'admin'
        ] );

        DB::table( 'users' )->insert( [
            'id'         => Uuid::generate()->string,
            'first_name' => "Catherine",
            'last_name'  => "Dohlhoff",
            'email'      => 'Catherine@swishproperties.co.za',
            'password'   => bcrypt( 'catherine20!7' ),
            'role'       => 'admin'
        ] );

        DB::table( 'users' )->insert( [
            'id'         => Uuid::generate()->string,
            'first_name' => "Craig",
            'last_name'  => "Getz",
            'email'      => 'craig@swishproperties.co.za',
            'password'   => bcrypt( 'craig20!7' ),
            'role'       => 'admin'
        ] );

    }
}
