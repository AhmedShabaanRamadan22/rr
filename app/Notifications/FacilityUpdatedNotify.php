<?php

namespace App\Notifications;

use App\Models\Facility;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FacilityUpdatedNotify extends Notification
{
    use Queueable;

    protected $facility;


    /**
     * Create a new notification instance.
     */
    public function __construct(Facility $facility)
    {
        $this->facility = $facility;
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

    public function toDatabase(object $notifiable)
    {
        $facility = $this->facility;

        return array(
            'id' => $facility->id,
            'message' => $this->getMessage(),
            'url' => $this->getUrl(),
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    private function getMessage()
    {
        return trans('translation.facility-updated-notify-assignee' ,
            [
                'facility_name' => $this->facility->name,
            ]
        );
    }

    private function getUrl()
    {
        return route('facilities.show',$this->facility->id);
    }
}
