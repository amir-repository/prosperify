<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRescueStoredReceipt extends Model
{
    use HasFactory;

    protected $fillable = ['rescue_assignment_id', 'stored_amount'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d M Y H:i')
        );
    }

    public function rescueAssignment()
    {
        return $this->belongsTo(RescueAssignment::class);
    }

    public static function Create($food, $rescueAssignment)
    {
        $foodRescueStoredReceipt = new FoodRescueStoredReceipt();
        $foodRescueStoredReceipt->rescue_assignment_id = $rescueAssignment->id;
        $foodRescueStoredReceipt->stored_amount = $food->amount;
        $foodRescueStoredReceipt->save();

        return $foodRescueStoredReceipt;
    }
}
