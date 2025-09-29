<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentRescheduled extends Notification
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
            ->subject('Appointment Rescheduled')
            ->line('An appointment has been rescheduled.')
            ->line('Patient: ' . $this->appointment->user->name)
            ->line('New Date/Time: ' . $this->appointment->scheduled_time->format('M d, Y h:i A'))
            ->line('Reason: ' . ($this->appointment->reason ?? 'Not specified'))
            ->action('View Appointment', url('/appointments/' . $this->appointment->id));
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'message' => 'Appointment rescheduled to ' . $this->appointment->scheduled_time->format('M d, Y h:i A'),
            'user_name' => $this->appointment->user->name,
        ];
    }
}
