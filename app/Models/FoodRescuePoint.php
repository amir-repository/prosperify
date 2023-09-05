<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRescuePoint extends Model
{
    use HasFactory;

    protected $table = 'food_rescue_point';

    protected $fillable = ['point_id', 'food_rescue_stored_receipt_id', 'point'];
}
