<?php

namespace Portal\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Portal\User;
use Portal\Application;

class SendApplicationRenewalEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $userID;
    protected $applicationID;
    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct($applicationID, $userID)
    {
        $this->applicationID = $applicationID;
        $this->userID = $userID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::findOrFail($this->userID);
        // Send the email to the user this contract is for

        $application = Application::with('location')->find($this->applicationID);

        // The email will include the secure link to
        // review and approve the contract

        Mail::send('emails.application_renewal', [
            'application' => $application
        ], function ($m) use ($user) {
            $m->to($user->email);
            $m->subject('My Domain contract renewal');
            $m->from('noreply@mydomain.co.za');
        });


    }
}
