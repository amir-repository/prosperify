<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function donationUsers()
    {
        return $this->hasMany(DonationUser::class);
    }

    public function DonationFoodUser()
    {
        return $this->hasMany(DonationStatus::class);
    }
}
