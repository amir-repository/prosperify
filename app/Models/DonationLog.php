<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationLog extends Model
{
    use HasFactory;

    protected $fillable = ['donation_id', 'donation_status_id', 'donation_status_name', 'actor_id', 'actor_name', 'title', 'description', 'donation_date', 'user_id', 'recipient_id', 'recipient_name'];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public static function Create($donation, $user)
    {
        $donationLog = new DonationLog();
        $donationLog->donation_id = $donation->id;
        $donationLog->donation_status_id = $donation->donation_status_id;
        $donationLog->donation_status_name = $donation->donationStatus->name;
        $donationLog->actor_id = $user->id;
        $donationLog->actor_name = $user->name;
        $donationLog->title = $donation->title;
        $donationLog->description = $donation->description;
        $donationLog->donation_date = $donation->donation_date;
        $donationLog->user_id = $donation->user_id;
        $donationLog->recipient_id = $donation->recipient_id;
        $donationLog->recipient_name = $donation->recipient->name;
        $donationLog->save();
    }
}
