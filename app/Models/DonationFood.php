<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationFood extends Model
{
    use HasFactory;

    public $incrementing = true;

    protected $table = 'donation_food';

    public const PLANNED = 1;
    public const ADJUSTED_AFTER_PLANNED = 2;
    public const ASSIGNED = 3;
    public const ADJUSTED_AFTER_ASSIGNED = 4;
    public const LAUNCHED = 5;
    public const ADJUSTED_AFTER_LAUNCHED = 6;
    public const DELIVERED = 7;
    public const ADJUSTED_AFTER_DELIVERED = 8;

    protected $fillable = ['donation_id', 'food_id', 'amount', 'food_donation_status_id'];

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
}
