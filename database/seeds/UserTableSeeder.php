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


    }
}
