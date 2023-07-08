<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rescue extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_name',
        'pickup_address',
        'phone',
        'email',
        'title',
        'description',
        'rescue_date',
        'score',
        'rescue_status_id',
        'user_id'
    ];

    public const DIRENCANAKAN = 1;
    public const DIAJUKAN = 2;
    public const DIPROSES = 3;
    public const DIAMBIL = 4;
    public const DISIMPAN = 5;
    public const DIBATALKAN = 6;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_logs()
    {
        return $this->belongsToMany(User::class)->withPivot('id', 'status');
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class);
    }

    public function rescueStatus()
    {
        return $this->belongsTo(RescueStatus::class);
    }

    public function rescueUser()
    {
        return $this->hasMany(RescueUser::class);
    }
}
