<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'donation_date', 'user_id', 'donation_status', 'recipient_id'];

    public const PLANNED = 1;
    public const ASSIGNED = 2;
    public const LAUNCHED = 3;
    public const INCOMPLETED = 4;
    public const COMPLETED = 5;
    public const CANCELED = 6;

    public function donationStatus()
    {
        return $this->belongsTo(DonationStatus::class);
    }

    public function donationLogs()
    {
        return $this->hasMany(DonationLog::class);
    }
}
