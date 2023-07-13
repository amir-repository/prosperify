<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationFood extends Model
{
    use HasFactory;

    protected $fillable = ['amount_plan', 'outbound_result', 'donation_id', 'food_id'];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
