<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Database\Factories\UserFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $fillable = [
        'name',
        'email',
        'birthdate',
        'gender',
        'bio',
        'image',
        'role',
        'password',
    ];

    use HasFactory, Notifiable, HasApiTokens;

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function isAuthor(): bool {
        return $this->role === 'author';
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
        ];
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return match ($this->gender) {
            'male' => asset('images/man-avatar.png'),
            'female' => asset('images/woman-avatar.png'),
            'default' => asset('images/development.png'),
        };
    }
    public function books() {
        return $this->hasMany(Book::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }
}
