<?php

namespace Portal\Jobs;

use Illuminate\Bus\Queueable;
use Mail;
use Portal\Application;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendApplicationSubmittedEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $application;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $application
     *
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Mail::send('emails.SendApplicationSubmittedEmail', [
            'application' => $this->application,
        ], function ($message) {
            $message->to("info@mydomainliving.co.za");
            $message->subject('A new application has been made!');
        });

    }
}
