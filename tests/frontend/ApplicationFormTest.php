<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApplicationFormTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test the initial user creation section of the form
     *
     * @return void
     */
    public function testPassingStepZero()
    {

        $this->visit('/application-form')
            ->type('First', 'first_name')
            ->type('Last', 'last_name')
            ->type('test@portal.com', 'email')
            ->type('password', 'password')
            ->press('Save and continue')
            ->see('First Last')
            ->see('Profile');

    }

    public function testFailingEmailValidationStepZero()
    {

        $this->visit('/application-form')
            ->type('First', 'first_name')
            ->type('Last', 'last_name')
            ->type('test@', 'email')
            ->type('qwerty', 'password')
            ->press('Save and continue')
            ->seePageIs('/application-form')
            ->see('The email must be a valid email address.');

    }

    public function testFailingPasswordValidationStepZero()
    {

        $this->visit('/application-form')
            ->type('First', 'first_name')
            ->type('Last', 'last_name')
            ->type('test@email.com', 'email')
            ->type('q', 'password')
            ->press('Save and continue')
            ->seePageIs('/application-form')
            ->see('he password must be at least 6 characters.');

    }

    /**
     * Test Step 1 fields on the application form.
     *
     * @return void
     */
    public function testStepOne()
    {
        // $this->visit('/application-form')
        //      ->type('Tyrone', 'first_name')
        //      ->type('Swish', 'last_name')
        //      ->type('email@test.com', 'email')
        //      ->type('password', 'password');
        //
        // $this->type('2000041301035', 'sa_id_number')
        //      ->type('abcdef987654', 'passport_number')
        //      ->type('2000-04-13', 'dob')
        //      ->type('Texas', 'nationality')
        //      ->type('+27987654321', 'phone_mobile')
        //      ->type('02199944455', 'phone_home ')
        //      ->type('La La land somewhere out there.', 'current_address')
        //      ->type('Maried', 'marital_status')
        //      ->type('Maried Type', 'married_type');

        // $this->assertTrue(true);
    }
}
