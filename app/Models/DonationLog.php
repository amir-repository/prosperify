<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationLog extends Model
{
    use HasFactory;

    protected $fillable = ['donation_id', 'donation_status_id', 'donation_status_name', 'actor_id', 'actor_name', 'food_donation_plan', 'food_donation_result', 'title', 'description', 'score', 'recipient_id'];
}
