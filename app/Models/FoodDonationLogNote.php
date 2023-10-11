<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDonationLogNote extends Model
{
    use HasFactory;

    protected $fillable = ['food_donation_log_id', 'note'];

    public function foodDonationLog()
    {
        return $this->belongsTo(FoodDonationLog::class);
    }

    public static function Create($foodDonationLog, $note)
    {
        $foodDonationLogNote = new FoodDonationLogNote();
        $foodDonationLogNote->food_donation_log_id = $foodDonationLog->id;
        $foodDonationLogNote->note = $note;
        $foodDonationLogNote->save();
    }
}
