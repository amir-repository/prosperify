<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'donation_date', 'user_id', 'donation_status_id', 'recipient_id', 'priority_donation_date'];

    public const PLANNED = 1;
    public const ASSIGNED = 2;
    public const INCOMPLETED = 3;
    public const COMPLETED = 4;
    public const FAILED = 5;

    protected function donationDateHumanize(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) =>  Carbon::parse($attributes['donation_date'])->format('d M Y H:i'),
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) =>  Carbon::parse($value)->format('d M Y H:i'),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class)->withPivot('amount');
    }

    public function donationStatus()
    {
        return $this->belongsTo(DonationStatus::class);
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function donationLogs()
    {
        return $this->hasMany(DonationLog::class);
    }

    public function donationFoods()
    {
        return $this->hasMany(DonationFood::class);
    }
}
