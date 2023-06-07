<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRescue extends Model
{
    use HasFactory;

    protected $table = 'food_rescue';

    protected $fillable = ['user_id', 'rescue_id'];
}
