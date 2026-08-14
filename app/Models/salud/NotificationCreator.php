<?php

namespace App\Models\salud;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationCreator
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function create($data)
    {
        DB::table('notifications')->insert([
            'id' => $data['id'] ?? Str::uuid()->toString(),
            'type' => $data['type'],
            'notifiable_type' => 'App\Models\Usuario',
            'notifiable_id' => $this->userId,
            'data' => is_array($data['data']) ? json_encode($data['data']) : $data['data'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => null,
        ]);
    }
}
