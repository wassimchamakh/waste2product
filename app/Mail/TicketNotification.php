<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $event;
    public $user;
    public $ticketNumber;
    public $ticketUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
        $this->event = $participant->event;
        $this->user = $participant->user;
        $this->ticketNumber = \App\Helpers\TicketHelper::generateTicketNumber($participant);
        $this->ticketUrl = route('Events.ticket.view', [
            'event' => $this->event->id,
            'participant' => $this->participant->id
        ]);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Votre Billet - ' . $this->event->title)
                    ->markdown('emails.ticket-notification');
    }
}
