<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class RecipientLog extends Model
{
    use HasFactory;

    protected $fillable = ['recipient_id', 'user_id', 'actor_id', 'actor_name', 'recipient_status_id', 'recipient_status_name'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y H:i')
        );
    }
}
