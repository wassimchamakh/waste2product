<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectLikedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $likerName;
    protected $projectTitle;
    protected $projectId;

    public function __construct($likerName, $projectTitle, $projectId)
    {
        $this->likerName = $likerName;
        $this->projectTitle = $projectTitle;
        $this->projectId = $projectId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->likerName . ' a liké votre projet: ' . $this->projectTitle,
            'liker' => $this->likerName,
            'project_title' => $this->projectTitle,
            'project_id' => $this->projectId,
        ];
    }
}
