<?php

namespace App\Models\salud;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotifiableUser
{
    use Notifiable;

    public $id;
    public $email;
    public $nombres;
    public $apellidos;
    public $name;
    private $attributes = [];

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function getKey()
    {
        return $this->id;
    }

    public function getMorphClass()
    {
        return 'App\Models\User';
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    public function notifications()
    {
        return new NotificationCreator($this->id);
    }
}
