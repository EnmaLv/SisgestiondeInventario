<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $keysToCheck = is_array($keys) ? $keys : [$keys];
        foreach ($this->roles ?? [] as $r) {
            $nombreRol = is_object($r) ? ($r->nombre ?? '') : ($r['nombre'] ?? '');
            if (in_array(mb_strtolower($nombreRol), ['administrador', 'secretaria de bienestar'])) {
                return true;
            }
        }

        $extra = is_array($this->extra_permissions)
            ? $this->extra_permissions
            : (is_string($this->extra_permissions) ? json_decode($this->extra_permissions, true) : []);

        $userDeny = $extra['deny'] ?? [];
        $userAllow = $extra['allow'] ?? [];

        $rolePermissions = collect($this->roles)
            ->pluck('menu_permissions')
            ->flatten()
            ->filter()
            ->unique()
            ->all();

        foreach ($keysToCheck as $key) {
            if (in_array($key, $userDeny)) {
                continue;
            }

            if (in_array($key, $userAllow)) {
                return true;
            }

            if (in_array($key, $rolePermissions)) {
                return true;
            }
        }

        return false;
    }

    public function paciente()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id_usuario');
    }

    public function psicologo()
    {
        return $this->belongsTo(Usuario::class, 'psicologo_id', 'id_usuario');
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

    public function tieneRol(array|string $roles): bool
    {
        $roles = (array) $roles;

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

    public static function obtenerUsuarioPorId($id)
    {
        $userRaw = DB::table('usuario')
            ->select('usuario.*', DB::raw("CONCAT(nombres, ' ', apellidos) as name"))
            ->where('id_usuario', $id)
            ->first();

        if ($userRaw) {
            $user = new self($userRaw);

            if (!isset($user->profile_photo_path)) {
                $user->profile_photo_path = null;
            }

            $user->profile_photo_url = !empty($user->profile_photo_path) ? Storage::disk('public')->url($user->profile_photo_path) : null;
            $initials = strtoupper(substr($user->nombres ?? '', 0, 1) . substr($user->apellidos ?? '', 0, 1));
            $user->avatar = $user->profile_photo_url ?: ($initials ?: 'PR');

            $user->primera_cita = DB::table('citas')
                ->where('user_id', $id)
                ->whereNotNull('fecha')
                ->orderBy('fecha', 'asc')
                ->value('fecha');

            return $user;
        }

        return null;
    }
}
