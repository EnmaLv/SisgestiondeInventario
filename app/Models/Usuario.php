<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use App\Models\Rol;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;

    protected $fillable = [
        'id_persona',
        'id_perfil',
        'username',
        'password',
        'master_key',
        'security_questions',
        'extra_permissions',
    ];

    protected $hidden = ['password', 'master_key'];

    protected $casts = [
        'security_questions' => 'array',
        'extra_permissions' => 'array',
    ];

    public function canAccessMenu($keys): bool
    {
        // Convertir a arreglo si se pasa una sola clave
        $keysToCheck = is_array($keys) ? $keys : [$keys];

        // 1. Bypass para Administrador y Secretaria de Bienestar (Super-Admins)
        foreach ($this->roles ?? [] as $r) {
            $nombreRol = is_object($r) ? ($r->nombre ?? '') : ($r['nombre'] ?? '');
            if (in_array(mb_strtolower($nombreRol), ['administrador', 'secretaria de bienestar'])) {
                return true;
            }
        }

        // 2. Extraer sobreescrituras explícitas del usuario (deny / allow)
        $extra = is_array($this->extra_permissions)
            ? $this->extra_permissions
            : (is_string($this->extra_permissions) ? json_decode($this->extra_permissions, true) : []);

        $userDeny = $extra['deny'] ?? [];
        $userAllow = $extra['allow'] ?? [];

        // 3. Extraer permisos provenientes de los roles del usuario
        $rolePermissions = collect($this->roles)
            ->pluck('menu_permissions')
            ->flatten()
            ->filter()
            ->unique()
            ->all();

        // 4. Evaluar cada clave solicitada (si al menos una es accesible, retorna true)
        foreach ($keysToCheck as $key) {
            // A. Denegado explícito individual
            if (in_array($key, $userDeny)) {
                continue;
            }

            // B. Permitido explícito individual
            if (in_array($key, $userAllow)) {
                return true;
            }

            // C. Permitido por rol
            if (in_array($key, $rolePermissions)) {
                return true;
            }
        }

        return false;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

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

    public function tieneRol(array $roles): bool
    {
        return $this->roles()
            ->where(function ($query) use ($roles) {
                $query->whereIn('slug', $roles)
                    ->orWhereIn('nombre', $roles);
            })
            ->exists();
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
