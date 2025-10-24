<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    /**
     * Create a project-related notification
     */
    public static function projectCreated(User $user, $projectTitle, $projectId)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'project',
            'icon' => 'fa-project-diagram',
            'color' => 'green',
            'title' => 'Nouveau projet créé',
            'message' => "Votre projet \"{$projectTitle}\" a été créé avec succès et est en attente de validation.",
            'link' => route('projects.show', $projectId),
            'is_read' => false,
        ]);
    }

    /**
     * Project approved notification
     */
    public static function projectApproved(User $user, $projectTitle, $projectId)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'project',
            'icon' => 'fa-check-circle',
            'color' => 'green',
            'title' => 'Projet approuvé !',
            'message' => "Félicitations ! Votre projet \"{$projectTitle}\" a été approuvé et est maintenant visible publiquement.",
            'link' => route('projects.show', $projectId),
            'is_read' => false,
        ]);
    }

    /**
     * Project comment notification
     */
    public static function projectCommented(User $user, $projectTitle, $commenterName, $projectId)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'comment',
            'icon' => 'fa-comment',
            'color' => 'yellow',
            'title' => 'Nouveau commentaire sur votre projet',
            'message' => "{$commenterName} a commenté votre projet \"{$projectTitle}\".",
            'link' => route('projects.show', $projectId),
            'is_read' => false,
        ]);
    }

    /**
     * Event registration notification
     */
    public static function eventRegistered(User $user, $eventTitle, $eventId)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'event',
            'icon' => 'fa-calendar-check',
            'color' => 'purple',
            'title' => 'Inscription confirmée',
            'message' => "Votre inscription à l'événement \"{$eventTitle}\" a été confirmée.",
            'link' => route('Events.show', $eventId),
            'is_read' => false,
        ]);
    }

    /**
     * Event reminder notification
     */
    public static function eventReminder(User $user, $eventTitle, $eventDate, $eventId)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'event',
            'icon' => 'fa-calendar',
            'color' => 'purple',
            'title' => 'Rappel : Événement à venir',
            'message' => "L'événement \"{$eventTitle}\" aura lieu le {$eventDate}.",
            'link' => route('Events.show', $eventId),
            'is_read' => false,
        ]);
    }

    /**
     * Event message from organizer
     */
    public static function eventMessage(User $user, $eventTitle, $message, $eventId)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'message',
            'icon' => 'fa-envelope',
            'color' => 'pink',
            'title' => 'Message de l\'organisateur',
            'message' => "Nouveau message concernant \"{$eventTitle}\" : {$message}",
            'link' => route('Events.show', $eventId),
            'is_read' => false,
        ]);
    }

    /**
     * Dechet reserved notification
     */
    public static function dechetReserved(User $user, $dechetTitle, $reserverName, $dechetId)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'dechet',
            'icon' => 'fa-recycle',
            'color' => 'orange',
            'title' => 'Déchet réservé',
            'message' => "{$reserverName} souhaite récupérer votre déchet \"{$dechetTitle}\".",
            'link' => route('dechets.show', $dechetId),
            'is_read' => false,
        ]);
    }

    /**
     * Dechet available notification
     */
    public static function dechetAvailable(User $user, $dechetTitle, $quantity, $dechetId)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'dechet',
            'icon' => 'fa-recycle',
            'color' => 'orange',
            'title' => 'Nouveau déchet disponible',
            'message' => "{$quantity} de \"{$dechetTitle}\" est maintenant disponible.",
            'link' => route('dechets.show', $dechetId),
            'is_read' => false,
        ]);
    }

    /**
     * Tutorial update notification
     */
    public static function tutorialUpdated(User $user, $tutorialTitle, $tutorialId)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'tutorial',
            'icon' => 'fa-book',
            'color' => 'blue',
            'title' => 'Tutoriel mis à jour',
            'message' => "Le tutoriel \"{$tutorialTitle}\" que vous suivez a été mis à jour.",
            'link' => route('tutorials.show', $tutorialId),
            'is_read' => false,
        ]);
    }

    /**
     * Tutorial completed notification
     */
    public static function tutorialCompleted(User $user, $tutorialTitle, $tutorialId)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'tutorial',
            'icon' => 'fa-graduation-cap',
            'color' => 'green',
            'title' => 'Tutoriel terminé !',
            'message' => "Félicitations ! Vous avez terminé le tutoriel \"{$tutorialTitle}\".",
            'link' => route('tutorials.show', $tutorialId),
            'is_read' => false,
        ]);
    }

    /**
     * User mentioned in comment
     */
    public static function userMentioned(User $user, $mentionerName, $context, $link)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'comment',
            'icon' => 'fa-at',
            'color' => 'blue',
            'title' => 'Vous avez été mentionné',
            'message' => "{$mentionerName} vous a mentionné dans {$context}.",
            'link' => $link,
            'is_read' => false,
        ]);
    }

    /**
     * System notification (for all users or specific user)
     */
    public static function systemNotification($title, $message, $userId = null, $icon = 'fa-info-circle', $color = 'blue', $link = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => 'system',
            'icon' => $icon,
            'color' => $color,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false,
        ]);
    }

    /**
     * Welcome notification for new users
     */
    public static function welcomeUser(User $user)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'icon' => 'fa-hand-wave',
            'color' => 'green',
            'title' => 'Bienvenue sur Waste2Product !',
            'message' => "Merci de nous rejoindre, {$user->name} ! Explorez nos projets, tutoriels et événements pour commencer votre aventure éco-responsable.",
            'link' => route('home'),
            'is_read' => false,
        ]);
    }
}
