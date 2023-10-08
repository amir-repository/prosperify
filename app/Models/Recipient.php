<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use HasFactory;

    public const SUBMITTED = 1;
    public const ACCEPTED = 2;
    public const REJECTED = 3;
    public const PROSPERED = 4;
    public const CANCELED = 5;

    protected $fillable = ["nik", "name", "address", 'phone', 'family_members', 'photo', 'recipient_status_id'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y H:i')
        );
    }

    public function recipientStatus()
    {
        return $this->belongsTo(RecipientStatus::class);
    }

    public function recipientLogs()
    {
        return $this->hasMany(RecipientLog::class);
    }
}
