<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['donation_food_id', 'user_id', 'donation_id', 'food_id'];

    public function donationFood()
    {
        return $this->belongsTo(DonationFood::class);
    }
}
