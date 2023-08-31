<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RescueLog extends Model
{
    use HasFactory;

    protected $fillable = ['rescue_id', 'rescue_status_id', 'rescue_status_name', 'actor_id', 'actor_name', 'food_rescue_plan', 'food_rescue_result', 'donor_name', 'pickup_address', 'phone', 'email', 'title', 'description', 'rescue_date', 'score', 'user_id'];

    public static function Create($user, $rescue)
    {
        $rescueLog = new RescueLog();
        $rescueLog->rescue_id = $rescue->id;
        $rescueLog->rescue_status_id = $rescue->rescue_status_id;
        $rescueLog->rescue_status_name = $rescue->rescueStatus->name;
        $rescueLog->actor_id = $user->id;
        $rescueLog->actor_name = $user->name;
        $rescueLog->donor_name = $rescue->donor_name;
        $rescueLog->pickup_address = $rescue->pickup_address;
        $rescueLog->phone = $rescue->phone;
        $rescueLog->email = $rescue->email;
        $rescueLog->title = $rescue->title;
        $rescueLog->description = $rescue->description;
        $rescueLog->rescue_date = Carbon::createFromFormat('d M Y H:i', $rescue->rescue_date);
        $rescueLog->user_id = $rescue->user_id;
        $rescueLog->save();
    }
}
