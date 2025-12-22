<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServerInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $token;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $url = route('loyalty.invitations.show', [
            'email' => $this->user->email,
            'token' => $this->token,
        ]);

        return $this->subject('Activa tu acceso de mesero')
            ->view('emails.loyalty.server-invitation')
            ->with([
                'user' => $this->user,
                'url' => $url,
            ]);
    }
}
