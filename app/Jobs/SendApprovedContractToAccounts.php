<?php

namespace Portal\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Portal\Unit;
use Portal\Contract;
use Portal\Application;
use Portal\User;

class SendApprovedContractToAccounts implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $contract;
    protected $application;
    protected $unit;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param Contract $contract
     */
    public function __construct(User $user, Contract $contract, Application $application, Unit $unit, $data)
    {
        $this->user = $user;
        $this->contract = $contract;
        $this->application = $application;
        $this->unit = $unit;
        $this->data = $data;
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
            'application' => $this->application,
            'unit'    => $this->unit,
            'user' => $this->user,
            'data'  => $this->data,
        ], function ($message) {
            $message->to("Catherine@swishproperties.co.za");
            $message->cc("info@mydomainliving.co.za");
            $message->subject('A user has approved their contract');
        });

    }
}
