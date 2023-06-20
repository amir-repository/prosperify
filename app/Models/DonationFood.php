<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationFood extends Model
{
    use HasFactory;

    protected $fillable = ['outbound_plan', 'outbound_plan_date', 'outbound_result', 'outbound_result_date', 'donation_id', 'food_id', 'vault_id'];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
