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
    private $token;
    private $base_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->base_url = env('APP_URL');
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
                'resetLink' => $this->base_url . 'auth/reset-password/' . $this->token
            ]);
    }
}
