<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MetricsReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $metricType;
    protected string $messageText;

    public function __construct(string $metricType, string $messageText)
    {
        $this->metricType = $metricType;
        $this->messageText = $messageText;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Metrics Reminder',
            'metric' => $this->metricType,
            'message' => $this->messageText,
        ];
    }
}


