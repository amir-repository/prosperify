<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RescueUser extends Model
{

    protected $table = 'rescue_user';

    use HasFactory;

    public function rescuePhotos()
    {
        return $this->hasMany(RescuePhoto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rescue()
    {
        return $this->belongsTo(Rescue::class);
    }

    public function rescueStatus()
    {
        return $this->belongsTo(RescueStatus::class);
    }
}
