<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Portal\Jobs\SendContractToUserEmail;
use MailThief\Testing\InteractsWithMail;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class ContractsApiTest extends Tests\TestCase
{

    use DatabaseMigrations;

    // Provides convenient testing traits and initializes MailThief
    use InteractsWithMail;

    /*
     * Test an contract submission fails on user id validation
     */
    public function testFailUserIdValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/contracts', [])
            ->assertResponseStatus(422)
            ->seeJson([
                "user_id" => ["The user id field is required."]
            ]);

    }

    /*
     * Test an contract submission fails on unit id validation
     */
    public function testFailUnitIdValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);

        $this->actingAs($user)
            ->json('POST', '/contracts', [
                'user_id' => $user->id
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "unit_id" => ["The unit id field is required."]
            ]);

    }

    /*
     * Test an contract submission fails on start date validation
     */
    public function testFailStartDateValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/contracts', [
                'user_id' => $user->id,
                'unit_id' => $unit->id,
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "unit_occupation_date" => ["The unit occupation date field is required."]
            ]);

    }

    /*
     * Test an contract submission fails on end date validation
     */
    public function testFailEndDateValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/contracts', [
                'user_id'    => $user->id,
                'unit_id'    => $unit->id,
                'start_date' => \Carbon\Carbon::now()
            ])
            ->assertResponseStatus(422)
            ->seeJson([
                "unit_vacation_date" => ["The unit vacation date field is required."]
            ]);

    }

    /*
     * Test an item submission fails on unit type validation
     */
    public function testPassValidation()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $application = factory(Portal\Application::class)->create([
            'user_id'       => $user->id,
            'unit_location' => $location->id,
            'unit_type'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/contracts/' . $application->id, [
                'user_id'              => $user->id,
                'unit_id'              => $unit->id,
                'application_id'       => $application->id,
                'unit_occupation_date' => '2020-01-01',
                'unit_vacation_date'   => '2020-11-01',
                'status'               => 'pending'
            ])
            ->assertResponseStatus(200);

        $pdfName = ucfirst(preg_replace('/[^\w-]/', '', $user->first_name)) . ucfirst(preg_replace('/[^\w-]/', '', $user->last_name)) . \Carbon\Carbon::today()->toDateString();
        $uploaded = 'contracts' . DIRECTORY_SEPARATOR . $pdfName . '.pdf';
        unlink(storage_path($uploaded));

    }

    /**
     * Test for PDF creation
     */
    public function testPDFIsGenerated()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $application = factory(Portal\Application::class)->create([
            'user_id'       => $user->id,
            'unit_location' => $location->id,
            'unit_type'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/contracts/' . $application->id, [
                'user_id'              => $user->id,
                'unit_id'              => $unit->id,
                'application_id'       => $application->id,
                'unit_occupation_date' => '2021-01-01',
                'unit_vacation_date'   => '2021-11-01',
                'status'               => 'pending'
            ])
            ->assertResponseStatus(200);

        $pdfName = ucfirst(preg_replace('/[^\w-]/', '', $user->first_name)) . ucfirst(preg_replace('/[^\w-]/', '', $user->last_name)) . \Carbon\Carbon::today()->toDateString();
        $uploaded = 'contracts' . DIRECTORY_SEPARATOR . $pdfName . '.pdf';
        $this->assertFileExists(storage_path($uploaded));

        // remove the file after the test
        unlink(storage_path($uploaded));
    }

    /**
     * Check email sent is correct
     */
    public function testSecureContractEmailContents()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $application = factory(Portal\Application::class)->create([
            'user_id'       => $user->id,
            'unit_location' => $location->id,
            'unit_type'     => $unitType->id
        ]);

        $this->actingAs($user)
            ->json('POST', '/contracts/' . $application->id, [
                'user_id'              => $user->id,
                'unit_id'              => $unit->id,
                'application_id'       => $application->id,
                'unit_occupation_date' => '2021-01-01',
                'unit_vacation_date'   => '2021-11-01',
                'status'               => 'pending'
            ])
            ->assertResponseStatus(200);

        // Check that an email was sent to this email address
        $this->seeMessageFor($user->email);

        // Make sure the email has the correct subject
        $this->seeMessageWithSubject('My Domain contract for review');

        // Make sure the email was sent from the correct address
        $this->seeMessageFrom('noreply@mydomain.co.za');

        // Make sure the email contains text in the body of the message
        // Default is to search the html rendered view
        $this->assertTrue($this->lastMessage()->contains('Here is the latest contract for your approval'));

        $pdfName = ucfirst(preg_replace('/[^\w-]/', '', $user->first_name)) . ucfirst(preg_replace('/[^\w-]/', '', $user->last_name)) . \Carbon\Carbon::today()->toDateString();
        $uploaded = 'contracts' . DIRECTORY_SEPARATOR . $pdfName . '.pdf';
        unlink(storage_path($uploaded));

    }

    /**
     * When saving a contract we need to be sure we are saving
     * the contract items, these are specific items of value on
     * the contract that must be listed.
     */
    public function testContractItemsAreSaved()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);

        $application = factory(Portal\Application::class)->create([
            'user_id'       => $user->id,
            'unit_location' => $location->id,
            'unit_type'     => $unitType->id
        ]);

        $items = factory(Portal\Item::class, 5)->create();

        $this->actingAs($user)
            ->json('POST', '/contracts/' . $application->id, [
                'user_id'              => $user->id,
                'unit_id'              => $unit->id,
                'application_id'       => $application->id,
                'unit_occupation_date' => '2021-01-01',
                'unit_vacation_date'   => '2021-11-01',
                'status'               => 'pending',
                'items'                => $items
            ])
            ->assertResponseStatus(200);

        $response = $this->actingAs($user)->seeInDatabase('contract_items', [
            'name'  => $items[0]->name,
            'value' => $items[0]->cost
        ]);

        $pdfName = ucfirst(preg_replace('/[^\w-]/', '', $user->first_name)) . ucfirst(preg_replace('/[^\w-]/', '', $user->last_name)) . \Carbon\Carbon::today()->toDateString();
        $uploaded = 'contracts' . DIRECTORY_SEPARATOR . $pdfName . '.pdf';
        unlink(storage_path($uploaded));

    }

    /**
     * Test that user with a secure link that's not theirs
     * cannot view the page
     */
    public function testUnAuthoriseUserCanNotViewContract()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $user2 = factory(Portal\User::class)->create([
            'role' => 'tenant'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);
        $application = factory(Portal\Application::class)->states('forApproval')->create([
            'user_id'       => $user->id,
            'unit_location' => $location->id,
            'unit_type'     => $unitType->id
        ]);
        $secureLink = encrypt($user->email . '##' . $user->first_name);
        factory(Portal\Contract::class)->create([
            'unit_id'        => $unit->id,
            'user_id'        => $user->id,
            'application_id' => $application->id,
            'secure_link'    => $secureLink
        ]);

        $this->actingAs($user2);
        $this->call('GET', '/contracts/secure/' . $secureLink);
        $this->assertResponseStatus(401);

    }

    /**
     * Test that user with a secure link that's not theirs
     * cannot view the page
     */
    public function testUserCanViewContract()
    {

        $user = factory(Portal\User::class)->create([
            'role' => 'application'
        ]);
        $location = factory(Portal\Location::class)->create();
        $unitType = factory(Portal\UnitType::class)->create([
            'location_id' => $location->id
        ]);
        $unit = factory(Portal\Unit::class)->create([
            'location_id' => $location->id,
            'type_id'     => $unitType->id
        ]);
        $application = factory(Portal\Application::class)->states('forApproval')->create([
            'user_id'       => $user->id,
            'unit_location' => $location->id,
            'unit_type'     => $unitType->id
        ]);
        $secureLink = encrypt($user->email . '##' . $user->first_name);

        factory(Portal\Contract::class)->create([
            'unit_id'        => $unit->id,
            'user_id'        => $user->id,
            'application_id' => $application->id,
            'secure_link'    => $secureLink
        ]);

        $this->actingAs($user)
            ->visit('/contracts/secure/' . $secureLink)
            ->see('This is your contract');

    }

}