<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDiff extends Model
{
    use HasFactory;

    protected $fillable = ['food_id', 'amount', 'on_food_rescue_status_id', 'food_rescue_status_id'];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function onFoodRescueStatus()
    {
        return $this->belongsTo(FoodRescueStatus::class);
    }

    public function foodRescueStatus()
    {
        return $this->belongsTo(FoodRescueStatus::class);
    }

    public static function Create($food, $diffAmount, $user)
    {
        $foodDiff = new FoodDiff();
        $foodDiff->food_id = $food->id;
        $foodDiff->amount = $diffAmount;
        $foodDiff->on_food_rescue_status_id = $food->food_rescue_status_id;
        $foodDiff->food_rescue_status_id = Food::DISCARDED;
        $foodDiff->actor_id = $user->id;
        $foodDiff->actor_name = $user->name;
        $foodDiff->save();
    }
}
