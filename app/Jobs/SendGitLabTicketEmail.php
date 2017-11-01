<?php

namespace Portal\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendGitLabTicketEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    private $user;
    private $userIssue;
    private $location;

    /**
     * Create a new job instance.
     * @param $user
     * @param $user_issue
     */
    public function __construct( $user, $userIssue, $location )
    {

        $this->user = $user;
        $this->userIssue = $userIssue;
        $this->location = $location;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send the email to GitLab

        Mail::send('emails.gitlabIssue', [
            'user' => $this->user,
            'userIssue' => $this->userIssue,
            'location' => $this->location,
        ], function ($m) {
            $m->to("incoming+swish-properties/portal-mydomain@gitlab.com");
            $m->subject($this->userIssue);
            $m->from('noreply@mydomain.co.za');
        });
    }
}
