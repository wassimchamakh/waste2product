<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Models\Notification as NotificationModel;

class CustomDatabaseChannel
{
    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification)
    {
        // Get the notification data
        $data = method_exists($notification, 'toDatabase') 
            ? $notification->toDatabase($notifiable)
            : $notification->toArray($notifiable);
        
        // Create notification in custom format
        return NotificationModel::create([
            'user_id' => $notifiable->id,
            'type' => $data['type'] ?? class_basename($notification),
            'icon' => $data['icon'] ?? 'fas fa-bell',
            'color' => $data['color'] ?? 'blue',
            'title' => $data['title'] ?? 'Nouvelle notification',
            'message' => $data['message'] ?? '',
            'link' => $data['link'] ?? null,
            'is_read' => false,
        ]);
    }
}
