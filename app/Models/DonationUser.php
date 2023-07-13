<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationUser extends Model
{
    use HasFactory;

    protected $table = 'donation_user';

    protected $fillable = ['donation_id', 'user_id', 'donation_status_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function donationStatus()
    {
        return $this->belongsTo(DonationStatus::class);
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
