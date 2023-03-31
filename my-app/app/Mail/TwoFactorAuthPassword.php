<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TwoFactorAuthPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $tfa_token = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code, $email)
    {
        $this->code = $code;
        $this->email = $email;
    }


    public function build()
    {
        return $this->to($this->email)
            ->subject('２段階認証のパスワード')
            ->view('two_factor_auth.password')
            ->with('code', $this->code);
    }
}
