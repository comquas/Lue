<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Leave;
use App\User;

class ApplyLeave extends Mailable
{
    use Queueable, SerializesModels;

    public $leave, $user, $supervisor;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Leave $leave, User $user, User $supervisor)
    {
        
        $this->leave = $leave;
        $this->user = $user;
        $this->supervisor = $supervisor;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Apply Leave from '.$this->leave->user->name.' to '.$this->supervisor->name)->markdown('emails.applyLeave');
    }
}
