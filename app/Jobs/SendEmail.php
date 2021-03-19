<?php

namespace App\Jobs;

use App\Models\Email;
use App\Mail\GeneralEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Email  $email
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job by sending the email.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email->recipient)->send(new GeneralEmail($this->email));
    }

    /**
     * If the jobs fails, set the status of the email as 'failed'.
     *
     * @return void
     */
    public function failed()
    {
        Email::where('id', $this->email->id)->update(['status' => 'failed']);
    }
}
