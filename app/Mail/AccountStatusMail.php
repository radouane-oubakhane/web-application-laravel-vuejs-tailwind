<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use function Symfony\Component\String\s;

class AccountStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nom;
    public $status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nom, $status)
    {
        $this->nom = $nom;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->status)
            return $this->from('support@lab.com')
                ->subject('Votre compte a été réactivé')
                ->view('emails.accountreactiveemail');
        return $this->from('support@lab.com')
            ->subject('Votre compte a été désactivé')
            ->view('emails.accountdisabledemail');
    }
}
