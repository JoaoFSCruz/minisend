<?php

namespace App\Listeners;

use App\Models\Email;
use Illuminate\Mail\Events\MessageSent;

class EmailSent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Mail\Events\MessageSent  $event
     *
     * @return void
     */
    public function handle(MessageSent $event)
    {
        Email::where('id', $event->message->email->id)->update(['status' => 'sent']);
    }
}
