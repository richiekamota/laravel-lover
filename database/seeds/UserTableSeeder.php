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
            'first_name' => "First",
            'last_name'  => "Last",
            'email'      => 'first_last@test.com',
            'password'   => bcrypt( 'qwerty' ),
            'role'       => 'admin'
        ] );

        DB::table( 'users' )->insert( [
            'id'         => Uuid::generate()->string,
            'first_name' => "Craig",
            'last_name'  => "Getz",
            'email'      => 'craig@swishproperties.co.za',
            'password'   => bcrypt( 'craig2016' ),
            'role'       => 'admin'
        ] );

        DB::table( 'users' )->insert( [
            'id'         => Uuid::generate()->string,
            'first_name' => "Catherine",
            'last_name'  => "Dohlhoff",
            'email'      => 'catherine@mydomainliving.co.za',
            'password'   => bcrypt( 'catherine20!7' ),
            'role'       => 'admin'
        ] );

        DB::table( 'users' )->insert( [
            'id'         => Uuid::generate()->string,
            'first_name' => "Natasha",
            'last_name'  => "Du Toit",
            'email'      => 'natasha@mydomainliving.co.za',
            'password'   => bcrypt( 'natasha20!7' ),
            'role'       => 'admin'
        ] );


    }
}
