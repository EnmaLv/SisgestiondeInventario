<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use App\Models\Rol;

class Usuario extends Authenticatable
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;

    protected $fillable = [
        'id_persona',
        'id_perfil',
        'username',
        'password',
        'role',
        'master_key',
        'security_questions',
        'extra_permissions',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'security_questions' => 'array',
        'extra_permissions' => 'array',
    ];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'id_perfil', 'id_perfil');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_usuario', 'id_usuario', 'id_rol');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setMasterKeyAttribute($value)
    {
        $this->attributes['master_key'] = Hash::make($value);
    }

    public function verifyMasterKey($candidate)
    {
        try {
            $stored = $this->getOriginal('master_key') ?: $this->master_key;
            if (is_null($stored) || $stored === '') {
                return false;
            }

            return Hash::check($candidate, $stored);
        } catch (\RuntimeException $e) {
            $stored = $this->getOriginal('master_key') ?: $this->master_key;
            if (is_string($stored) && $stored !== '') {
                return password_verify($candidate, $stored);
            }

            return false;
        }
    }

    public function verifyPassword($candidate)
    {
        try {
            $stored = $this->getOriginal('password') ?: $this->password;
            if (is_null($stored) || $stored === '') {
                return false;
            }

            return Hash::check($candidate, $stored);
        } catch (\RuntimeException $e) {
            $stored = $this->getOriginal('password') ?: $this->password;
            if (is_string($stored) && $stored !== '') {
                return password_verify($candidate, $stored);
            }

            return false;
        }
    }
}
