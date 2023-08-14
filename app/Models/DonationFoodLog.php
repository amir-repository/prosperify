<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationFoodLog extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'donation_id', 'food_id', 'assigner_id', 'assigner_name', 'volunteer_id', 'volunteer_name', 'actor_id', 'actor_name', 'donation_food_status_id', 'donation_food_status_name', 'amount', 'expired_date', 'unit_id', 'unit_name', 'photo', 'vault_id', 'vault_name'];
}
