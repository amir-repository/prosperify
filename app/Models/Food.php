<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Food extends Model
{
    use HasFactory;

    public const PLANNED = 1;
    public const ADJUSTED_AFTER_PLANNED = 2;
    public const SUBMITTED = 3;
    public const ADJUSTED_AFTER_SUBMITTED = 4;
    public const PROCESSED = 5;
    public const ADJUSTED_AFTER_PROCESSED = 6;
    public const ASSIGNED = 7;
    public const ADJUSTED_AFTER_ASSIGNED = 8;
    public const TAKEN = 9;
    public const STORED = 10;
    public const ADJUSTED_BEFORE_STORED = 11;
    public const ADJUSTED_AFTER_STORED = 12;
    public const REJECTED = 13;
    public const CANCELED = 14;
    public const DISCARDED = 15;

    protected $fillable = ['rescue_id', 'vault_id', 'name', 'detail', 'expired_date', 'amount', 'stored_at', 'photo', 'category_id', 'sub_category_id', 'unit_id', 'food_rescue_status_id'];

    protected function expiredDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y')
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y')
        );
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (float $value) => ($value / 1000),
            set: fn (float $value) => (int)($value * 1000)
        );
    }

    public function rescue()
    {
        return $this->belongsTo(Rescue::class);
    }

    public function foodRescueStatus()
    {
        return $this->belongsTo(FoodRescueStatus::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function foodRescueLogs()
    {
        return $this->hasMany(FoodRescueLog::class);
    }

    public function foodAssignments()
    {
        return $this->hasMany(RescueAssignment::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function vault()
    {
        return $this->belongsTo(Vault::class);
    }

    public function rescueSchedule()
    {
        return $this->hasOne(RescueSchedule::class);
    }

    public function donations()
    {
        return $this->belongsToMany(Donation::class);
    }
}
