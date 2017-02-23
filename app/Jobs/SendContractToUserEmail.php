<?php

namespace Portal\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Portal\Application;
use Portal\User;
use Portal\Location;

class SendContractToUserEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    private $filePath;
    private $userId;
    private $secureLink;
    private $applicationId;

    /**
     * Create a new job instance.
     * @param $filePath
     * @param $userId
     * @param $secureLink
     * @param $applicationId
     * @internal param $secure_link
     */
    public function __construct( $filePath, $userId, $secureLink, $applicationId )
    {

        $this->filePath = $filePath;
        $this->userId = $userId;
        $this->secureLink = $secureLink;
        $this->applicationId = $applicationId;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $user = User::findOrFail($this->userId);
        // Send the email to the user this contract is for

        $application = Application::with('location')->find($this->applicationId);

        // The email will include the secure link to
        // review and approve the contract

        Mail::send('emails.contract', [
            'secureLink' => $this->secureLink,
            'application' => $application
        ], function ($m) use ($user) {
            $m->to($user->email);
            $m->subject('My Domain contract for review');
            $m->from('noreply@mydomain.co.za');
            $m->attach($this->filePath);
        });


    }
}
