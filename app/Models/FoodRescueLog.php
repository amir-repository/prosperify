<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRescueLog extends Model
{
    use HasFactory;

    protected $fillable = ['assigner_id', 'assigner_name', 'volunteer_id', 'volunteer_name', 'amount', 'unit_id', 'unit_name', 'photo'];
}
