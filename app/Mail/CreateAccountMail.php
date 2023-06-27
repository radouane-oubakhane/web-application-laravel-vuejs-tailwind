<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $password, $email)
    {
        $this->user = $user;
        $this->password = $password;
        $this->email = $email;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@lab.com')
            ->subject('Information de votre compte')
            ->view('emails.createaccountmail');
    }
}
