<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    use HasFactory;


    public static function columnNames()
    {
        return array(
            'id' => 'id',
            'message' => 'message',
            'url' => 'link',
            'created_at' => 'created_at',
            'read_at' => 'read_at',
            'action' => 'action',
        );
    }


    public static function columnInputs()
    {
        return array(
            'data' => 'text',
            'created_at' => 'text',
            // 'read_at' => 'text',
            // 'updated_at' => 'text',
        );
    }

    public function scopeForAuthUser($query)
    {
        return $query->where('notifiable_id', auth()->id()) // المستخدم الحالي
                     ->where('notifiable_type', 'App\Models\User'); // تأكد من تطابق النوع
    }

    public function scopeLastsWithLimit($query,$limit = 5)
    {
        return $query->orderBy('created_at','desc')->take($limit);
    }
    
    public function switchRead(){
        $this->read() ? $this->markAsUnread() : $this->markAsRead();
    }
}
