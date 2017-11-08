<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Portal\Jobs\SendContractToUserEmail;
use MailThief\Testing\InteractsWithMail;

class ApplicationProcessTest extends Tests\TestCase
{

    use DatabaseMigrations;
    use DatabaseTransactions;

   // Provides convenient testing traits and initializes MailThief
    use InteractsWithMail;

    /**
     * Setup the tests on this page
     */
    public function setUp()
    {
      parent::setUp();

      $this->user = factory( Portal\User::class )->create( [
          'role' => 'application'
      ] );

    }

    /**
     * Check email sent is correct
     */
    public function testPendingApplicationEmailContents()
    {

      $userIssue = "This is the user issue";

      $this->actingAs( $this->user )
          ->json( 'POST', '/submit-admin-issue', [
              'user_issue' => $userIssue,
              'location'   => 'http://google.com'
          ] )
          ->assertResponseStatus( 200 );

      $this->seeMessageFor('incoming+swish-properties/portal-mydomain@gitlab.com');
      $this->seeMessageWithSubject($userIssue);
      $this->seeMessageFrom('noreply@mydomain.co.za');
      $this->assertTrue($this->lastMessage()->contains('Date:'));
      $this->assertTrue($this->lastMessage()->contains('User text: ' . $userIssue));

    }

}