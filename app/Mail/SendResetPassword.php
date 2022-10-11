<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $code,$user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code,$user)
    {
        $this->code = $code;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@Plexus.com', 'Plexus')
                    ->view('send-reset-password')
                    ->subject('Noreply Reset Password Code')
                    ->with([
                        "code"=>$this->code,
                        "user"=>$this->user,
                    ]);
    }
}
