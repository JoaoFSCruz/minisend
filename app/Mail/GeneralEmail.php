<?php

namespace App\Mail;

use App\Models\Attachment;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $email;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Email  $email
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->withSwiftMessage(function ($message) {
            $message->email = $this->email;
        });

        $this->from($this->email->sender)
            ->view('emails.general')
            ->subject($this->email->subject)
            ->with([
                'text' => $this->email->text ?? '',
                'html' => $this->email->html ?? '',
            ]);

        $attachments = $this->email->attachments->pluck('filepath');
        foreach ($attachments as $path) {
            $this->attachFromStorage($path);
        }

        return $this;
    }
}
