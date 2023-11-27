<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;


class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public const DONOR = 'donor';
    public const VOLUNTEER = 'volunteer';
    public const ADMIN = 'admin';

    public function rescues()
    {
        return $this->hasMany(Rescue::class);
    }

    public function rescue_logs()
    {
        return $this->belongsToMany(Rescue::class)->withPivot('status');
    }

    public function donation()
    {
        return $this->belongsToMany(Donation::class)->withPivot('id');
    }

    public function point()
    {
        return $this->hasOne(Point::class);
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function foodRescue()
    {
        return $this->belongsToMany(FoodRescue::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin');
    }
}
