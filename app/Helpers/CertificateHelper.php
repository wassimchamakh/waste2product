<?php

namespace App\Helpers;

use App\Models\Participant;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class CertificateHelper
{
    /**
     * Generate a certificate for a participant
     */
    public static function generateCertificate(Participant $participant)
    {
        $event = $participant->event;
        $user = $participant->user;

        // Create certificate HTML
        $html = self::getCertificateHTML($participant, $event, $user);

        // For now, we'll return the HTML
        // In production, you might want to use a PDF library like DomPDF or TCPDF
        return $html;
    }

    /**
     * Get certificate HTML template
     */
    private static function getCertificateHTML(Participant $participant, Event $event, $user)
    {
        $eventDate = \Carbon\Carbon::parse($event->date_start)->locale('fr')->isoFormat('D MMMM YYYY');
        $issueDate = \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
        
        return view('certificates.template', [
            'participant' => $participant,
            'event' => $event,
            'user' => $user,
            'eventDate' => $eventDate,
            'issueDate' => $issueDate,
        ])->render();
    }

    /**
     * Check if participant is eligible for certificate
     */
    public static function isEligibleForCertificate(Participant $participant)
    {
        $event = $participant->event;
        
        // Must have attended the event
        if ($participant->attendance_status !== 'attended') {
            return false;
        }

        // Event must be in the past
        if (\Carbon\Carbon::parse($event->date_end)->isFuture()) {
            return false;
        }

        return true;
    }

    /**
     * Generate certificate number
     */
    public static function generateCertificateNumber(Participant $participant)
    {
        $event = $participant->event;
        $year = \Carbon\Carbon::parse($event->date_start)->format('Y');
        $eventId = str_pad($event->id, 4, '0', STR_PAD_LEFT);
        $participantId = str_pad($participant->id, 4, '0', STR_PAD_LEFT);
        
        return "CERT-{$year}-{$eventId}-{$participantId}";
    }
}
