<?php

namespace Portal\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Portal\Contract;
use Portal\User;

class SendApprovedContractToAccounts implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $contract;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param Contract $contract
     */
    public function __construct(User $user, Contract $contract)
    {
        $this->user = $user;
        $this->contract = $contract;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Mail::send('emails.sendApprovedContractToAccounts', [
            'contract' => $this->contract,
            'user' => $this->user
        ], function ($message) {
            $message->to("accounts@swish.co.za");
            $message->subject('A user has approved their contract');
        });

    }
}