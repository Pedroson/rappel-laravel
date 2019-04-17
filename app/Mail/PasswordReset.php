<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $resetLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $resetLink)
    {
        $this->user = $user;
        $this->resetLink = $resetLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@rappel.co.uk')
            ->subject('Password Reset')
            ->view('emails.passwordreset', [
                'user'  => $this->user,
                'resetLink' => $this->resetLink
            ]);
    }
}
