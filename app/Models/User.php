<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
<<<<<<< HEAD
        'role',
        'master_key',
        'security_questions',
=======
>>>>>>> 357e4cdaba75ae2dc079ffec813e4fa3fb3f6164
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
<<<<<<< HEAD
        'master_key',
=======
>>>>>>> 357e4cdaba75ae2dc079ffec813e4fa3fb3f6164
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
<<<<<<< HEAD
            'security_questions' => 'array',
        ];
    }

    /**
     * Mutator to encrypt master key before saving to DB.
     */
    public function setMasterKeyAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['master_key'] = null;
            return;
        }

        $this->attributes['master_key'] = encrypt($value);
    }

    /**
     * Helper to verify a given master key against stored encrypted value.
     */
    public function verifyMasterKey(string $candidate): bool
    {
        if (empty($this->master_key)) {
            return false;
        }

        try {
            $stored = decrypt($this->getAttributes()['master_key']);
        } catch (\Exception $e) {
            return false;
        }

        return hash_equals($stored, $candidate);
    }
=======
        ];
    }
>>>>>>> 357e4cdaba75ae2dc079ffec813e4fa3fb3f6164
}
