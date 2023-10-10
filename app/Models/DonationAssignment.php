<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['volunteer_id', 'vault_id', 'assigner_id', 'donation_food_id', 'donation_id', 'food_id', 'assigner_id'];

    public function donationFood()
    {
        return $this->belongsTo(DonationFood::class);
    }
}
