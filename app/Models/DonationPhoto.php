<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationPhoto extends Model
{
    use HasFactory;

    protected $fillable = ["photo", "donation_user_id", "user_id"];

    public function donationUser()
    {
        return $this->belongsTo(DonationUser::class);
    }
}
