<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RescueSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'food_id', 'rescue_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function food()
    {
        return $this->hasOne(Food::class);
    }

    public static function Create($rescue, $food, $volunteerID)
    {
        $rescueSchedule = new RescueSchedule();
        $rescueSchedule->user_id = $volunteerID;
        $rescueSchedule->food_id = $food->id;
        $rescueSchedule->rescue_date = Carbon::createFromFormat('d M Y H:i', $rescue->rescue_date);
        $rescueSchedule->save();
    }
}
