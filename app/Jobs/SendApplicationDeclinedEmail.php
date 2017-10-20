<?php

namespace Portal\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Portal\User;

class SendApplicationDeclinedEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $reason;
    protected $application;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $application
     * @param $reason
     */
    public function __construct(User $user, $application, $reason)
    {
        $this->user = $user;
        $this->reason = $reason;
        $this->application = $application;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $email = $this->user->email;

        Mail::send('emails.sendApplicationDeclinedEmail', [
            'applicant' => $this->user,
            'application' => $this->application,
            'reason' => $this->reason,
        ], function ($message) use ($email) {
            $message->to($email);
            $message->cc($this->application->email);
            $message->subject('Your application has been declined');
        });

    }
}
