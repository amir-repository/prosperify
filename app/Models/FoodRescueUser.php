<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRescueUser extends Model
{
    use HasFactory;

    protected $table = 'food_rescue_user';

    protected $fillable = ['user_id', 'food_rescue_id', 'amount', 'photo', 'rescue_status_id', 'unit_id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rescueStatus()
    {
        return $this->belongsTo(RescueStatus::class);
    }

    public function points()
    {
        return $this->belongsToMany(Point::class)->withPivot('point');
    }
}
