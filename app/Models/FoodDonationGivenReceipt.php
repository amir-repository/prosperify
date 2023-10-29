<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FoodDonationGivenReceipt extends Model
{
    use HasFactory;

    protected $fillable = ['donation_assignment_id', 'given_amount', 'recipient_signature'];

    protected function givenAmount(): Attribute
    {
        return Attribute::make(
            get: fn (float $value) => ($value / 1000),
            set: fn (float $value) => (int)($value * 1000)
        );
    }

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
