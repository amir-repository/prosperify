<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Rescue extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'pickup_address', 'rescue_date', 'donor_name', 'phone', 'email'];

    public const PLANNED = 1;
    public const SUBMITTED = 2;
    public const PROCESSED = 3;
    public const ASSIGNED = 4;
    public const COMPLETED = 5;
    public const REJECTED = 6;

    protected function rescueDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y H:i')
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y H:i')
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class)->withPivot('id');
    }

    public function rescueStatus()
    {
        return $this->belongsTo(RescueStatus::class);
    }

    public function rescueUser()
    {
        return $this->hasMany(RescueUser::class);
    }
}
