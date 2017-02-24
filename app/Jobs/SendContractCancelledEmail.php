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

    protected $contract;
    protected $userID;
    protected $application;
    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct($contract, $userID )
    {
        $this->contract = $contract;
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

        $contract = $this->contract;
        $this->application = Application::find($contract->application_id);

        // The email will include the secure link to
        // review and approve the contract

        Mail::send('emails.contract_cancelled', [
            'contract' => $contract
        ], function ($m) use ($user) {
            $m->to($user->email);
            $m->cc($this->application->email);
            $m->subject('My Domain contract cancelled');
            $m->from('noreply@mydomain.co.za');
        });

    }
}
