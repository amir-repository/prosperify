<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRescue extends Model
{
    use HasFactory;

    protected $table = 'food_rescue';

    protected $fillable = ['user_id', 'rescue_id', 'food_id', 'food_rescue_status_id', 'rescue_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function rescue()
    {
        return $this->belongsTo(Rescue::class);
    }

    public function foodRescueStatus()
    {
        return $this->belongsTo(FoodRescueStatus::class);
    }

    public function foodRescueUsers()
    {
        return $this->hasMany(FoodRescueUser::class);
    }
}
