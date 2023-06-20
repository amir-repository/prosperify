<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationUser extends Model
{
    use HasFactory;

    protected $table = 'donation_user';

    protected $fillable = ['donation_id', 'user_id', 'status'];
}
