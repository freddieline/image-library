<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $photoPath;

    public $heading;

    public function __construct( $photoPath )
    {
        $this->photoPath = $photoPath;
        $this->heading = "Your photo is available";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('noreply-yourphoto@coke.ch')
            ->view('email.base', ['photoPath', $this->photoPath ]);
    }
}