<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRescueStoredReceipt extends Model
{
    use HasFactory;

    protected $fillable = ['rescue_assignment_id', 'stored_amount'];

    public function rescueAssignment()
    {
        return $this->hasOne(RescueAssignment::class);
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
