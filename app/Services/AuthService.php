<?php

namespace App\Services;

use App\Models\Usuario;
use App\Models\Persona;
use App\Models\ConfiguracionSistema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class AuthService
{
    public function register(array $personaData, array $userData): Usuario
    {
        return DB::transaction(function () use ($personaData, $userData) {
            $estatusId = $this->ensureEstatus();
            $sedeId = $this->ensureSede(); 

            // 1. Verificar si es el primer usuario del sistema
            $isFirstUser = Usuario::count() === 0;
            $perfilNombre = $isFirstUser ? 'Administrador' : 'Usuario';

            // 2. Asegurar que el perfil correcto exista
            $perfilObj = $this->ensurePerfil($perfilNombre, $estatusId);
            $perfilId = $perfilObj->id_perfil;

            if (empty($personaData['id_perfil'])) {
                $personaData['id_perfil'] = $perfilId;
            }
            if (empty($personaData['id_sede'])) {
                $personaData['id_sede'] = $sedeId;
            }

            $persona = Persona::create($personaData);

            // 3. Crear el usuario
            $usuario = Usuario::create([
                'id_persona' => $persona->id_persona,
                'id_perfil'  => $perfilId,
                'username'   => $userData['username'],
                'password'   => $userData['password'], // Asumiendo que el modelo Usuario tiene un mutador para el Hash
                // Si es el primer usuario, podrías asignar una master_key por defecto aquí
                'master_key' => $isFirstUser ? bcrypt('admin123') : null,
            ]);

            // 4. Si es el primer usuario, también debemos asegurar el ROL en la tabla pivote
            // ya que tu seeder usa 'rol_usuario'
            $this->assignRol($usuario->id_usuario, $perfilNombre);

            return $usuario;
        });
    }

    private function assignRol(int $usuarioId, string $rolNombre): void
    {
        // Buscamos el rol por nombre (en la tabla que use tu sistema, asumo 'roles')
        $rol = DB::table('rol')->where('nombre', $rolNombre)->first();
        
        if (!$rol) {
            $rolId = DB::table('rol')->insertGetId([
                'nombre' => $rolNombre,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $rolId = $rol->id_rol;
        }

        // Insertamos en la tabla pivote que mencionaste en tu seeder
        DB::table('rol_usuario')->updateOrInsert(
            ['id_usuario' => $usuarioId, 'id_rol' => $rolId],
            ['created_at' => now(), 'updated_at' => now()]
        );
    }

    private function ensureEstatus(): int
    {
        $row = DB::table('estatus')->orderBy('id_estatus')->first();
        if ($row) return $row->id_estatus;

        $id = DB::table('estatus')->insertGetId(['nombre_estatus' => 'Activo', 'created_at' => now(), 'updated_at' => now()]);
        return $id;
    }

    private function ensureSucursal(): int
    {
        $row = DB::table('sucursals')->orderBy('id')->first();
        if ($row) return $row->id;

        return DB::table('sucursals')->insertGetId([
            'nombre' => 'Acarigua',
            'direccion' => 'Avenida Circunvalacion Sur, Sector Bellas Artes',
            'telefono' => '0424-5556666',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    private function ensureSede(): int
    {
        $row = DB::table('sede')->orderBy('id_sede')->first();
        if ($row) return $row->id_sede;

        $sucursalId = $this->ensureSucursal();
        $id = DB::table('sede')->insertGetId([
            'nombre_sede' => 'Principal',
            'id_sucursal' => $sucursalId,
            'estatus'     => 1, 
            'created_at'  => now(),
            'updated_at'  => now()
        ]);
        
        return $id;
    }

    private function ensurePerfil(string $nombre, int $estatusId)
    {
        $row = DB::table('perfil')->where('nombre_perfil', $nombre)->first();
        if ($row) return (object)['id_perfil' => $row->id_perfil];

        $id = DB::table('perfil')->insertGetId([
            'nombre_perfil' => $nombre, 
            'id_estatus' => $estatusId, 
            'created_at' => now(), 
            'updated_at' => now()
        ]);
        return (object)['id_perfil' => $id];
    }

    public function validateCredentials(string $username, string $password): ?Usuario
    {
        $usuario = Usuario::where('username', $username)->first();
        if (!$usuario) {
            return null;
        }

        if (!Hash::check($password, $usuario->getOriginal('password'))) {
            return null;
        }

        return $usuario;
    }

    public function verifyMasterKeyForUsuario(Usuario $usuario, string $masterKey): bool
    {
        try {
            $isAdmin = $usuario->roles()->where('nombre', 'Administrador')->exists();
        } catch (\Throwable $e) {
            $perfil = $usuario->perfil()->first();
            $isAdmin = $perfil && $perfil->nombre_perfil === 'Administrador';
        }

        if ($isAdmin) {
            return ConfiguracionSistema::checkMasterKey($masterKey);
        }

        return true;
    }

    public function login(string $username, string $password, ?string $masterKey = null): Usuario
    {
        $usuario = $this->validateCredentials($username, $password);
        if (!$usuario) {
            throw new AuthenticationException('Credenciales inválidas');
        }

        try {
            $isAdmin = $usuario->roles()->where('nombre', 'Administrador')->exists();
        } catch (\Throwable $e) {
            $perfil = $usuario->perfil()->first();
            $isAdmin = $perfil && $perfil->nombre_perfil === 'Administrador';
        }

        if ($isAdmin) {
            if (empty($masterKey) || !ConfiguracionSistema::checkMasterKey($masterKey)) {
                throw new AuthenticationException('Llave maestra requerida o inválida');
            }
        }

        return $usuario;
    }
}
