<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationFoodUser extends Model
{
    use HasFactory;

    protected $table = "donation_food_user";

    protected $fillable = ['user_id', 'donation_food_id', 'amount_plan', 'amount_result', 'photo', 'donation_status_id'];

    public function donationStatus()
    {
        return $this->belongsTo(DonationStatus::class);
    }
}
