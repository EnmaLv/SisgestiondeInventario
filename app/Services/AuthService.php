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
    /**
     * Register persona and usuario within a transaction.
     * $personaData: array with persona fields
     * $userData: ['username','password']
     */
    public function register(array $personaData, array $userData): Usuario
    {
        return DB::transaction(function () use ($personaData, $userData) {
            // Ensure required lookup rows exist (estatus, estado_ve, sede, perfil)
            $estatusId = $this->ensureEstatus();
            $estadoVeId = $this->ensureEstadoVe();
            $sedeId = $this->ensureSede($estadoVeId);

            // Ensure a generic perfil for grouping users exists (Perfil groups persons/departments)
            $defaultPerfil = $this->ensurePerfil('Usuario', $estatusId);
            $perfilId = $defaultPerfil->id_perfil;

            // attach perfil and sede to persona data if not provided
            if (empty($personaData['id_perfil'])) {
                $personaData['id_perfil'] = $perfilId;
            }
            if (empty($personaData['id_sede'])) {
                $personaData['id_sede'] = $sedeId;
            }

            // insert persona (table 'persona' uses id_persona primary key)
            $persona = Persona::create($personaData);

            // create usuario
            $usuario = Usuario::create([
                'id_persona' => $persona->id_persona,
                'id_perfil' => $perfilId,
                'username' => $userData['username'],
                'password' => $userData['password'],
            ]);

            // Master key handling will be performed by the caller if needed based on assigned roles

            return $usuario;
        });
    }

    private function ensureEstatus(): int
    {
        $row = DB::table('estatus')->orderBy('id_estatus')->first();
        if ($row) return $row->id_estatus;

        // create default estatus
        $id = DB::table('estatus')->insertGetId(['nombre_estatus' => 'Activo', 'created_at' => now(), 'updated_at' => now()]);
        return $id;
    }

    private function ensureEstadoVe(): int
    {
        $row = DB::table('estados')->orderBy('id')->first();
        if ($row) return $row->id;

        $id = DB::table('estados')->insertGetId(['nombre_estado' => 'Default', 'created_at' => now(), 'updated_at' => now()]);
        return $id;
    }

    private function ensureSede(int $estadoVeId): int
    {
        $row = DB::table('sede')->orderBy('id_sede')->first();
        if ($row) return $row->id_sede;

        $id = DB::table('sede')->insertGetId(['nombre_sede' => 'Principal', 'id_estado' => $estadoVeId, 'created_at' => now(), 'updated_at' => now()]);
        return $id;
    }

    private function ensurePerfil(string $nombre, int $estatusId)
    {
        $row = DB::table('perfil')->where('nombre_perfil', $nombre)->first();
        if ($row) return (object)['id_perfil' => $row->id_perfil];

        $id = DB::table('perfil')->insertGetId(['nombre_perfil' => $nombre, 'id_estatus' => $estatusId, 'created_at' => now(), 'updated_at' => now()]);
        return (object)['id_perfil' => $id];
    }

    /**
     * Login: validate username/password and require master_key for Administrador
     * returns Usuario on success
     */
    /**
     * Validate credentials only (first step). Returns Usuario if credentials ok.
     */
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

    /**
     * Verify master key for a given usuario (by id)
     */
    public function verifyMasterKeyForUsuario(Usuario $usuario, string $masterKey): bool
    {
        // Check if usuario has Administrador role
        try {
            $isAdmin = $usuario->roles()->where('nombre', 'Administrador')->exists();
        } catch (\Throwable $e) {
            // Fallback to perfil check if roles table not available
            $perfil = $usuario->perfil()->first();
            $isAdmin = $perfil && $perfil->nombre_perfil === 'Administrador';
        }

        if ($isAdmin) {
            return ConfiguracionSistema::checkMasterKey($masterKey);
        }

        // non-admins don't require master key
        return true;
    }

    /**
     * Full login verifying credentials and master key if required.
     */
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
