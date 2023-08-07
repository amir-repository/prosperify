<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RescueLog extends Model
{
    use HasFactory;

    protected $fillable = ['rescue_id', 'rescue_status_id', 'rescue_status_name', 'actor_id', 'actor_name', 'food_rescue_plan', 'food_rescue_result', 'donor_name', 'pickup_address', 'phone', 'email', 'title', 'description', 'rescue_date', 'score', 'user_id'];
}
