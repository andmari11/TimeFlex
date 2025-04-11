<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'role',
        'section_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function forms()
    {
        return $this->belongsTo(Form::class);
    }
    public function shifts()
    {
        return $this->belongsToMany(Shift::class);
    }
    public function notifications()
    {
        $this->updateReadNotifications();
        return $this->hasMany(Notification::class)->latest()->take(10);
    }
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('read', false);
    }

    public function updateReadNotifications()
    {
        $ret= $this->hasMany(Notification::class);
        $ret->update(['read' => true]);

    }

    public function satisfactions()
    {
        return $this->HasMany(Satisfaction::class);
    }

    public function results()
    {
        return $this->HasMany(Result::class);
    }

    public function holidays()
    {
        return $this->belongsToMany(Holidays::class);
    }
}
