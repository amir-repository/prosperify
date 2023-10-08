<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'donation_date', 'user_id', 'donation_status', 'recipient_id'];

    public function donationStatus()
    {
        return $this->belongsTo(DonationStatus::class);
    }

    public function donationLogs()
    {
        return $this->hasMany(DonationLog::class);
    }
}
