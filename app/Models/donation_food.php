<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class donation_food extends Model
{
    use HasFactory;

    protected $table = 'donation_food';

    protected $fillable = ['donation_id', 'food_id', 'amount', 'food_donation_status_id'];
}
