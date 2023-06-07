<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RescuePhoto extends Model
{
    use HasFactory;

    protected $fillable = ['photo', 'rescue_user_id', 'user_id'];
}
