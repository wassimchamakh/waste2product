<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $event;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
        $this->event = $participant->event;
        $this->user = $participant->user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $certificateUrl = route('Events.certificate.view', [
            'event' => $this->event->id,
            'participant' => $this->participant->id
        ]);

        return $this->subject('Votre Certificat de Participation - ' . $this->event->title)
                    ->markdown('emails.certificate-notification', [
                        'participant' => $this->participant,
                        'event' => $this->event,
                        'user' => $this->user,
                        'certificateUrl' => $certificateUrl,
                    ]);
    }
}
