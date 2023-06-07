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
}
