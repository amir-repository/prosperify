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

    public function volunteer()
    {
        return $this->belongsTo(User::class);
    }

    public static function Create($volunteerID, $vaultID, $user, $donationFood)
    {
        $donationAssignment = new DonationAssignment();
        $donationAssignment->volunteer_id = $volunteerID;
        $donationAssignment->vault_id = $vaultID;
        $donationAssignment->assigner_id = $user->id;
        $donationAssignment->donation_food_id = $donationFood->id;
        $donationAssignment->donation_id = $donationFood->donation_id;
        $donationAssignment->food_id = $donationFood->food_id;
        $donationAssignment->save();
    }
}
