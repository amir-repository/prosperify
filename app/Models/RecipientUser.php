<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipientUser extends Model
{
    use HasFactory;

    protected $table = 'recipient_user';

    protected $fillable = ['recipient_id', 'user_id', 'recipient_status_id'];
}
