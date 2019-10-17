<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App;

class Feedback extends Mailable
{
    use Queueable, SerializesModels;

    public $confirm;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($confirm)
    {
        $this->confirm = $confirm;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.username'), config('app.name'))
                    ->subject('Customer letter. Symon SKM '.config('app.url'))
                    ->view('mails.feedback');                      
    }
}
