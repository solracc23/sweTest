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
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }
    /**
     * Geheimschlüssel für die ID-Verschleierung
     */
    private const ID_SECRET = 847291;
    private const ID_OFFSET = 10000000;

    /**
     * Generiert einen 8-stelligen Schüler-Code aus der User-ID
     */
    public function getStudentCode(): int
    {
        return ($this->id ^ self::ID_SECRET) + self::ID_OFFSET;
    }

    /**
     * Wandelt einen Schüler-Code zurück in eine User-ID
     */
    public static function decodeStudentCode(int $code): int
    {
        return ($code - self::ID_OFFSET) ^ self::ID_SECRET;
    }
}
