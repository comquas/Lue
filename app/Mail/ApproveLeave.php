<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Leave;
use App\User;

class ApproveLeave extends Mailable
{
    use Queueable, SerializesModels;

    public $leave;
    public $user;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Leave $leave, User $user)
    {
        $this->leave = $leave;
        $this->user = $user;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Approved Leave to ".$this->leave->user->name." by ".$this->user->name)->markdown('emails.approveLeave');
    }
}
