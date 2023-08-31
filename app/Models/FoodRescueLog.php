<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FoodRescueLog extends Model
{
    use HasFactory;

    protected $fillable = ['rescue_id', 'food_id', 'assigner_id', 'assigner_name', 'volunteer_id', 'volunteer_name', 'actor_id', 'actor_name', 'food_rescue_status_id', 'food_rescue_status_name', 'amount', 'expired_date', 'unit_id', 'unit_name', 'photo'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y H:i')
        );
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
