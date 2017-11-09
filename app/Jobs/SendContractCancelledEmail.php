<?php

namespace Portal\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Portal\User;
use Portal\Application;

class SendContractCancelledEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $applicationID;
    protected $userID;
    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct($applicationID, $userID )
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

        //Send the email to the user this contract is for
        $user = User::findOrFail($this->userID);
        $application = Application::findOrFail($this->applicationID);

        // The email will include the secure link to
        // review and approve the contract

        Mail::send('emails.contract_cancelled', [
            'application' => $application
        ], function ($m) use ($user, $application) {
            $m->to($user->email);
            $m->cc(($application->email != '') ? $application->email : 'noreply@mydomain.co.za');
            $m->subject('My Domain contract cancelled');
            $m->from('noreply@mydomain.co.za');
        });

    }
}
