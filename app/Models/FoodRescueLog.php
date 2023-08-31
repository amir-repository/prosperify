<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FoodRescueLog extends Model
{
    use HasFactory;

    protected $fillable = ['rescue_id', 'food_id', 'assigner_id', 'assigner_name', 'volunteer_id', 'volunteer_name', 'actor_id', 'actor_name', 'food_rescue_status_id', 'food_rescue_status_name', 'amount', 'expired_date', 'unit_id', 'unit_name', 'photo'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y H:i')
        );
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public static function Create($user, $rescue, $food)
    {
        $foodRescueLog = new FoodRescueLog();
        $foodRescueLog->rescue_id = $rescue->id;
        $foodRescueLog->food_id = $food->id;
        $foodRescueLog->actor_id = $user->id;
        $foodRescueLog->actor_name = $user->name;
        $foodRescueLog->food_rescue_status_id = $food->food_rescue_status_id;
        $foodRescueLog->food_rescue_status_name = $food->foodRescueStatus->name;
        $foodRescueLog->amount = $food->amount;
        $foodRescueLog->expired_date = Carbon::createFromFormat('d M Y', $food->expired_date);
        $foodRescueLog->unit_id = $food->unit_id;
        $foodRescueLog->unit_name = $food->unit->name;
        $foodRescueLog->photo = $food->photo;
        $foodRescueLog->save();
    }
}
