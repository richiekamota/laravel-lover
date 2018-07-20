<?php

namespace Portal\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Portal\User;
use Portal\Contract;
use Portal\Application;

class SendContractDeclinedEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $contract;
    protected $application;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Contract $contract, Application $application)
    {
        $this->user = $user;
        $this->contract = $contract;
        $this->application = $application;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Mail::send('emails.sendContractDeclinedEmailToAdmin', [
            'contract' => $this->contract,
            'application' => $this->application,
            'user' => $this->user
        ], function ($message) {
            $message->to(env('MAIL_ADMIN_USER', 'info@mydomainliving.co.za'));
            $message->subject('An applicant has declined a contract');
        });

    }
}
