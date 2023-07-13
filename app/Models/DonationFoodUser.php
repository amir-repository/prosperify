<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationFoodUser extends Model
{
    use HasFactory;

    protected $table = "donation_food_user";

    protected $fillable = ['user_id', 'donation_food_id', 'amount', 'photo', 'donation_status_id', 'unit_id'];

    public function donationStatus()
    {
        return $this->belongsTo(DonationStatus::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function donationFood()
    {
        return $this->belongsTo(DonationFood::class);
    }
}
