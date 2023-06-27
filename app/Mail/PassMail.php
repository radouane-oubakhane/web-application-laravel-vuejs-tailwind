<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PassMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    private $accept;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $password, $accept)
    {
        $this->user = $user;
        $this->password = $password;
        $this->accept = $accept;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->accept)
        return $this->from('support@lab.com')
                    ->subject('acceptance email')
                    ->view('emails.acceptanceemail');
        return $this->from('support@lab.com')
                    ->subject('refuse email')
                    ->view('emails.rejectemail');
    }
}
