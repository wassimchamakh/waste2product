<?php

namespace App\Helpers;

use App\Models\Participant;
use App\Models\Event;
use BaconQrCode\Renderer\Image\Svg;
use BaconQrCode\Writer;

class TicketHelper
{
    /**
     * Generate ticket number
     */
    public static function generateTicketNumber(Participant $participant)
    {
        $event = $participant->event;
        $year = \Carbon\Carbon::parse($event->date_start)->format('Y');
        $eventId = str_pad($event->id, 4, '0', STR_PAD_LEFT);
        $participantId = str_pad($participant->id, 4, '0', STR_PAD_LEFT);
        
        return "TKT-{$year}-{$eventId}-{$participantId}";
    }

    /**
     * Generate QR code data string
     */
    public static function generateQRData(Participant $participant)
    {
        $ticketNumber = self::generateTicketNumber($participant);
        
        // Create verification URL
        $verificationUrl = route('Events.ticket.verify', [
            'event' => $participant->event_id,
            'ticket' => $ticketNumber
        ]);
        
        return $verificationUrl;
    }

    /**
     * Generate QR code SVG
     */
    public static function generateQRCodeSVG(Participant $participant)
    {
        $data = self::generateQRData($participant);
        
        // Create QR code using BaconQrCode v1.0 API
        $renderer = new Svg();
        $renderer->setWidth(200);
        $renderer->setHeight(200);
        
        $writer = new Writer($renderer);
        
        return $writer->writeString($data);
    }

    /**
     * Check if participant can view ticket
     */
    public static function canViewTicket(Participant $participant)
    {
        // Can view if confirmed (for free events) or payment completed (for paid events)
        if ($participant->event->isFree()) {
            return in_array($participant->attendance_status, ['confirmed', 'attended']);
        } else {
            return $participant->payment_status === 'completed';
        }
    }

    /**
     * Get ticket status badge
     */
    public static function getTicketStatusBadge(Participant $participant)
    {
        if ($participant->attendance_status === 'attended') {
            return '<span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">✓ Présent</span>';
        }
        
        if ($participant->attendance_status === 'confirmed') {
            return '<span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">✓ Confirmé</span>';
        }
        
        if ($participant->attendance_status === 'cancelled') {
            return '<span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">✗ Annulé</span>';
        }
        
        return '<span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">⏳ En Attente</span>';
    }
}
