<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRescueStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function foodRescueUser()
    {
        return $this->hasMany(FoodRescueUser::class);
    }

    public function foodRescue()
    {
        return $this->hasMany(FoodRescue::class);
    }
}
