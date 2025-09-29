<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentCanceled extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Canceled')
            ->line('An appointment has been canceled.')
            ->line('Patient: ' . $this->appointment->user->name)
            ->line('Date/Time: ' . $this->appointment->scheduled_time->format('M d, Y h:i A'))
            ->line('Reason: ' . ($this->appointment->reason ?? 'Not specified'));
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'message' => 'Appointment scheduled for ' . $this->appointment->scheduled_time->format('M d, Y h:i A') . ' has been canceled',
            'user_name' => $this->appointment->user->name,
        ];
    }
}
