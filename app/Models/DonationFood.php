<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationFood extends Model
{
    use HasFactory;

    public const PLANNED = 1;
    public const LAUNCHED = 2;
    public const TAKEN = 3;
    public const DELIVERED = 4;
    public const GIVEN = 5;
    public const FAILED = 5;


    protected $fillable = ['donation_id', 'food_id', 'user_id', 'donation_food_status_id', 'amount_plan', 'amount_result', 'assigner_id', 'volunteer_id'];
}
