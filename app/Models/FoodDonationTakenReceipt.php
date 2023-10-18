<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDonationTakenReceipt extends Model
{
    use HasFactory;

    protected $fillable = ['donation_assignment_id', 'taken_amount', 'admin_signature'];

    public function donationAssignment()
    {
        return $this->belongsTo(DonationAssignment::class);
    }

    public static function Create($donationFood, $signature)
    {
        $foodDonationTakenReceipt = new FoodDonationTakenReceipt();
        $foodDonationTakenReceipt->donation_assignment_id = $donationFood->donationAssignments->last()->id;
        $foodDonationTakenReceipt->taken_amount = $donationFood->amount;
        $foodDonationTakenReceipt->admin_signature = $signature;
        $foodDonationTakenReceipt->save();
    }
}
