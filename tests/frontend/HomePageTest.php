<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomePageTest extends Tests\TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */

    public function testHomePage()
    {
        $this->get('/')
             ->see('MY DOMAIN IS YOUR DOMAIN');
    }
}
