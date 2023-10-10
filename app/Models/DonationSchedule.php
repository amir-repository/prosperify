<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['donation_food_id', 'user_id', 'donation_id', 'food_id', 'donation_date'];

    public function donationFood()
    {
        return $this->belongsTo(DonationFood::class);
    }

    public static function Create($volunteerID, $donationFood)
    {
        $donationSchedule = new DonationSchedule();
        $donationSchedule->user_id = $volunteerID;
        $donationSchedule->donation_food_id = $donationFood->id;
        $donationSchedule->donation_id = $donationFood->donation_id;
        $donationSchedule->food_id = $donationFood->food_id;
        $donationSchedule->donation_date = $donationFood->donation->donation_date;
        $donationSchedule->save();
    }
}
