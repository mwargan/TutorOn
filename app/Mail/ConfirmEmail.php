<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $key;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $request)
    {
        $this->key = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.confirm.email')->with('key', $this->key);
    }
}
