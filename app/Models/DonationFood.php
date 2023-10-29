<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DonationFood extends Model
{
    use HasFactory;

    public $incrementing = true;

    protected $table = 'donation_food';

    public const PLANNED = 1;
    public const ADJUSTED_AFTER_PLANNED = 2;
    public const ASSIGNED = 3;
    public const ADJUSTED_AFTER_ASSIGNED = 4;
    public const TAKEN = 5;
    public const ADJUSTED_AFTER_TAKEN = 6;
    public const GIVEN = 7;
    public const CANCELED = 8;
    public const TRASHED = 9;

    protected $fillable = ['donation_id', 'food_id', 'amount', 'food_donation_status_id'];

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (float $value) => ($value / 1000),
            set: fn (float $value) => (int)($value * 1000)
        );
    }

    public function foodDonationStatus()
    {
        return $this->belongsTo(FoodDonationStatus::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }


    public function foodDonationLogs()
    {
        return $this->hasMany(FoodDonationLog::class);
    }

    public function donationAssignments()
    {
        return $this->hasMany(DonationAssignment::class);
    }

    public function donationSchedules()
    {
        return $this->hasMany(DonationSchedule::class);
    }

    public function donationFoodDiffs()
    {
        return $this->hasMany(DonationFoodDiff::class);
    }
}
