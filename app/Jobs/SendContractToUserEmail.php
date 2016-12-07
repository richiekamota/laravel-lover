<?php

namespace Portal\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Portal\User;

class SendContractToUserEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    private $filePath;
    private $user_id;
    private $secureLink;

    /**
     * Create a new job instance.
     * @param $filePath
     * @param $user_id
     * @param $secureLink
     * @internal param $secure_link
     */
    public function __construct( $filePath, $user_id, $secureLink )
    {

        $this->filePath = $filePath;
        $this->user_id = $user_id;
        $this->secureLink = $secureLink;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        \Log::info( $this->filePath );
        \Log::info( $this->user_id );
        \Log::info( $this->secureLink );

        $user = User::findOrFail($this->user_id);
        // Send the email to the user this contract is for

        // The email will include the secure link to
        // review and approve the contract

        Mail::send('emails.contract', ['secureLink' => $this->secureLink], function ($m) use ($user) {
            $m->to($user->email);
            $m->subject('My Domain contract for review');
            $m->from('noreply@mydomain.co.za');
        });


    }
}
