<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationLog extends Model
{
    use HasFactory;

    protected $fillable = ['donation_id', 'donation_status_id', 'donation_status_name', 'actor_id', 'actor_name', 'title', 'description', 'donation_date', 'user_id'];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
