<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class RecipientLog extends Model
{
    use HasFactory;

    protected $fillable = ['recipient_id', 'recipient_status_id', 'recipient_status_name', 'actor_id', 'actor_name'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y H:i')
        );
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function recipientLogNote()
    {
        return $this->hasOne(RecipientLogNote::class);
    }
}
