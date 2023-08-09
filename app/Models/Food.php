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
    public const CANCELED = 8;

    protected $fillable = ['name', 'detail', 'expired_date', 'amount', 'stored_amount', 'photo', 'stored_amount', 'user_id', 'category_id', 'sub_category_id', 'unit_id', 'rejected_at', 'canceled_at'];

    protected function expiredDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y')
        );
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
