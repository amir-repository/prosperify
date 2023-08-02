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
    public const SUBMITTED = 2;
    public const PROCESSED = 3;
    public const ASSIGNED = 4;
    public const TAKEN = 5;
    public const STORED = 6;
    public const REJECTED = 7;

    protected $fillable = ['name', 'detail', 'expired_date', 'amount', 'in_stock', 'photo', 'unit_id', 'stored_at', 'user_id', 'category_id', 'sub_category_id'];

    protected function expiredDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y')
        );
    }

    public function vaults()
    {
        return $this->belongsToMany(Vault::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function donations()
    {
        return $this->belongsToMany(Donation::class)->withPivot('id', 'amount_plan', 'amount_result', 'food_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function rescues()
    {
        return $this->belongsToMany(Rescue::class)->withPivot('id');
    }
}
