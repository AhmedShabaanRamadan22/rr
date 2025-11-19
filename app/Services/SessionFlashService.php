<?php

namespace App\Services;


class SessionFlashService
{
    public static function setMessage($message,$type = 'success'){
        session()->flash('message', $message);
        session()->flash('alert-type', $type);
    }
}