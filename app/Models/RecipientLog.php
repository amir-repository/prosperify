<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipientLog extends Model
{
    use HasFactory;

    protected $fillable = ['recipient_id', 'user_id', 'actor_id', 'actor_name', 'recipient_status_id', 'recipient_status_name'];
}
