<?php

namespace App\Models;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationFoodDiff extends Model
{
    use HasFactory;

    protected $fillable = ["donation_food_id", "donation_id", "food_id", "amount", "on_food_donation_status_id", "food_donation_status_id"];

    public function donationFood()
    {
        return $this->belongsTo(DonationFood::class);
    }

    public static function Create($donationFood, $diffAmount)
    {
        $donationFoodDiff = new DonationFoodDiff();
        $donationFoodDiff->donation_food_id = $donationFood->id;
        $donationFoodDiff->donation_id = $donationFood->donation_id;
        $donationFoodDiff->food_id = $donationFood->food_id;
        $donationFoodDiff->amount = $diffAmount;
        $donationFoodDiff->food_donation_status_id = DonationFood::TRASHED;
        $donationFoodDiff->on_food_donation_status_id = $donationFood->food_donation_status_id;
        $donationFoodDiff->save();
    }
}
