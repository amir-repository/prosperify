<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointRescueUser extends Model
{
    use HasFactory;

    protected $table = 'point_rescue_user';

    protected $fillable = ['rescue_user_id', 'point_id', 'point'];
}
