<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRescueTakenReceipt extends Model
{
    use HasFactory;

    protected $fillable = ['rescue_assignment_id', 'taken_amount'];

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
        $foodRescueTakenReceipt = new FoodRescueTakenReceipt();
        $foodRescueTakenReceipt->rescue_assignment_id = $rescueAssignment->id;
        $foodRescueTakenReceipt->taken_amount = $food->amount;
        $foodRescueTakenReceipt->save();
    }
}
