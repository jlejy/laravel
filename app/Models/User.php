<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'happy_member';
    protected $primaryKey = 'number';
    const CREATED_AT = 'reg_date';
    const UPDATED_AT = 'mod_date';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_pass',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_pass',
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
            'user_pass' => 'hashed',
        ];
    }
    //2024.11.25 추가
    public function getEmailForPasswordReset()
    {
        return $this->user_id; // 필드 이름을 user_id로 변경
    }

    public function getAuthIdentifierName()
    {
        return 'user_id'; // 로그인 시 참조 필드
    }

    public function getAuthPassword()
    {
        return $this->user_pass;
    }
}
