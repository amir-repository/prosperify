<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDonationGivenReceipt extends Model
{
    use HasFactory;

    protected $fillable = ['donation_assignment_id', 'given_amount'];

    public function donationAssignment()
    {
        return $this->belongsTo(DonationAssignment::class);
    }
}
