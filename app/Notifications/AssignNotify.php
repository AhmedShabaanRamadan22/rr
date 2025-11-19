<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignNotify extends Notification
{
    use Queueable;

    protected $assignable;
    protected $assignable_class_name;
    protected $assigned_by;


    /**
     * Create a new notification instance.
     */
    public function __construct($assignable,$assigned_by)
    {
        $this->assignable = $assignable;
        $this->assignable_class_name = class_basename($assignable);
        $this->assigned_by = $assigned_by;
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
        $assignable = $this->assignable;

        return array(
            'id' => $assignable->id,
            'code' => $assignable->code??'-',
            'message' => $this->getMessage(),
            'assigned_by' => $this->assigned_by??'-',
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
        return trans('translation.has-assigned-to-you' ,[
            'assignable_class_name' => trans('translation.' . $this->assignable_class_name),
            'code' => $this->assignable->code]
        );
    }

    private function getUrl()
    {
        return route($this->assignable->getTable() . '.show',$this->assignable->id);
    }
}
