<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDonationGivenReceipt extends Model
{
    use HasFactory;

    protected $fillable = ['donation_assignment_id', 'given_amount', 'recipient_signature'];

    public function donationAssignment()
    {
        return $this->belongsTo(DonationAssignment::class);
    }

    public static function Create($donationFood, $signature)
    {
        $foodDonationGivenReceipt = new FoodDonationGivenReceipt();
        $foodDonationGivenReceipt->donation_assignment_id = $donationFood->donationAssignments->last()->id;
        $foodDonationGivenReceipt->given_amount = $donationFood->amount;
        $foodDonationGivenReceipt->recipient_signature = $signature;
        $foodDonationGivenReceipt->save();
    }
}
