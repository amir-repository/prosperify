<?php

namespace App\Models;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FoodDonationLog extends Model
{
    use HasFactory;

    protected $fillable = ['actor_id', 'actor_name', 'food_donation_status_id', 'food_donation_status_name', 'amount', 'unit_id', 'unit_name', 'photo', 'donation_food_id', 'donation_id', 'food_id'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) =>  Carbon::parse($value)->format('d M Y H:i'),
        );
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (float $value) => ($value / 1000),
            set: fn (float $value) => (int)($value * 1000)
        );
    }

    public function foodDonationLogNote()
    {
        return $this->hasOne(FoodDonationLogNote::class);
    }

    public static function Create($donationFood, $user, $photo)
    {
        $foodDonationLog = new FoodDonationLog();
        $foodDonationLog->donation_food_id = $donationFood->id;
        $foodDonationLog->donation_id = $donationFood->donation_id;
        $foodDonationLog->food_id = $donationFood->food_id;
        $foodDonationLog->actor_id = $user->id;
        $foodDonationLog->actor_name = $user->name;
        $foodDonationLog->food_donation_status_id = $donationFood->food_donation_status_id;
        $foodDonationLog->food_donation_status_name = $donationFood->foodDonationStatus->name;
        $foodDonationLog->amount = $donationFood->amount;
        $foodDonationLog->unit_id = $donationFood->food->unit_id;
        $foodDonationLog->unit_name = $donationFood->food->unit->name;
        $foodDonationLog->photo = $photo;
        $foodDonationLog->save();

        return $foodDonationLog;
    }
}
