<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Casts\Attribute;


class FoodRescue extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    protected $table = 'food_rescue';

    protected $fillable = ['rescue_id', 'food_id', 'user_id', 'food_rescue_status_id', 'assigner_id', 'volunteer_id', 'vault_id', 'amount_plan', 'amount_result'];

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y H:i')
        );
    }

    public function foodRescueStatus()
    {
        return $this->belongsTo(FoodRescueStatus::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vault()
    {
        return $this->belongsTo(Vault::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class);
    }
}
