<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRescueLogNote extends Model
{
    use HasFactory;

    protected $fillable = ['food_rescue_log_id', 'note'];

    public function foodRescueLog()
    {
        return $this->belongsTo(FoodRescueLog::class);
    }

    public static function Create($foodRescueLog, $note)
    {
        $foodRescueLogNote = new FoodRescueLogNote();
        $foodRescueLogNote->food_rescue_log_id = $foodRescueLog->id;
        $foodRescueLogNote->note = $note;
        $foodRescueLogNote->save();
    }
}
