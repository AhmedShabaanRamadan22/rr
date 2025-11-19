<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CrudNotify extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $model; 
    protected $action; 
    public function __construct($model,$action)
    {
        $this->model = $model;
        $this->action = $action; //* should be like 'create','update','delete','changeStatus' *// 
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
    public function toDatabase(object $notifiable)
    {
        $trans_array = [
            'sector' => $this->model->order_sector?->sector->label,
        ];
        if($this->model->code){
            $trans_array['code'] = $this->model->code;
        }
        if($this->model->support){
            $trans_array['sup_code'] = $this->model->support->code;
            $trans_array['sector'] = $this->model->support->order_sector?->sector->label;
        }
        if($this->model->from_sector){
            $trans_array['from_sector'] = $this->model->from_sector;
        }
        // dd($trans_array);
        $class_name = class_basename($this->model);
        // dd( ['en' => trans('translation.notify-' . $this->action . '-' . $class_name, $trans_array, 'en'),'ar' => trans('translation.notify-' . $this->action . '-' . $class_name, $trans_array, 'ar'),]);
        return [
            'data' => [
                'en' => trans('translation.notify-' . $this->action . '-' . $class_name, $trans_array, 'en'),
                'ar' => trans('translation.notify-' . $this->action . '-' . $class_name, $trans_array, 'ar'),
            ],
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
            // 'data' =>' Ticket ' . $this->ticket->code .' raised successfully'
        ];
    }
}
