<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectCommentedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $commenterName;
    protected $projectTitle;
    protected $projectId;

    public function __construct($commenterName, $projectTitle, $projectId)
    {
        $this->commenterName = $commenterName;
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
        return [\App\Channels\CustomDatabaseChannel::class];
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
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'comment',
            'icon' => 'fas fa-comment',
            'color' => 'blue',
            'title' => 'Nouveau commentaire',
            'message' => $this->commenterName . ' a commenté votre projet: ' . $this->projectTitle,
            'link' => '/projects/' . $this->projectId,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->commenterName . ' a commenté votre projet: ' . $this->projectTitle,
            'commenter' => $this->commenterName,
            'project_title' => $this->projectTitle,
            'project_id' => $this->projectId,
        ];
    }
}
