<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'description', 'score', 'food_donation_plan', 'food_donation_result', 'donation_date', 'recipient_id', 'user_id', 'donation_status_id'];

    public const PLANNED = 1;
    public const LAUNCHED = 2;
    public const INCOMPLETE = 3;
    public const COMPLETE = 4;
    public const FAILED = 4;
}
