<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'donation_status_id', 'donation_date', 'recipient_id'];

    public const DIRENCANAKAN = 1;
    public const BERLANGSUNG = 2;
    public const DISERAHKAN = 3;

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class)->withPivot('id', 'outbound_plan', 'outbound_result', 'food_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('id');
    }

    public function donationUsers()
    {
        return $this->hasMany(DonationUser::class);
    }

    public function donationStatus()
    {
        return $this->belongsTo(DonationStatus::class);
    }
}
