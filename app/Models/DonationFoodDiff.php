<?php

namespace App\Models;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DonationFoodDiff extends Model
{
    use HasFactory;

    protected $fillable = ["donation_food_id", "donation_id", "food_id", "amount", "on_food_donation_status_id", "food_donation_status_id", "actor_id", "actor_name"];

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (float $value) => ($value / 1000),
            set: fn (float $value) => (int)($value * 1000)
        );
    }

    public function donationFood()
    {
        return $this->belongsTo(DonationFood::class);
    }

    public function onFoodDonationStatus()
    {
        return $this->belongsTo(FoodDonationStatus::class);
    }

    public function foodDonationStatus()
    {
        return $this->belongsTo(FoodDonationStatus::class);
    }

    public static function Create($donationFood, $diffAmount, $actor)
    {
        $donationFoodDiff = new DonationFoodDiff();
        $donationFoodDiff->donation_food_id = $donationFood->id;
        $donationFoodDiff->donation_id = $donationFood->donation_id;
        $donationFoodDiff->food_id = $donationFood->food_id;
        $donationFoodDiff->amount = $diffAmount;
        $donationFoodDiff->food_donation_status_id = DonationFood::TRASHED;
        $donationFoodDiff->on_food_donation_status_id = $donationFood->food_donation_status_id;
        $donationFoodDiff->actor_id = $actor->id;
        $donationFoodDiff->actor_name = $actor->name;
        $donationFoodDiff->save();
    }
}
