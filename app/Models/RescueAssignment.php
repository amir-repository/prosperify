<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RescueAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['food_id', 'rescue_id', 'volunteer_id', 'vault_id', 'assigner_id'];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function rescue()
    {
        return $this->belongsTo(Rescue::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class);
    }

    public function vault()
    {
        return $this->belongsTo(Vault::class);
    }

    public static function Create($food, $rescue, $user, $volunteerID, $vaultID)
    {
        $rescueAssignment = new RescueAssignment();
        $rescueAssignment->food_id = $food->id;
        $rescueAssignment->rescue_id = $rescue->id;
        $rescueAssignment->volunteer_id = $volunteerID;
        $rescueAssignment->assigner_id = $user->id;
        $rescueAssignment->vault_id = $vaultID;
        $rescueAssignment->save();
    }
}
