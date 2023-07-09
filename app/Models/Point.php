<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $fillable = ['point', 'user_id'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function foodRescueUsers()
    {
        return $this->belongsToMany(FoodRescueUser::class)->withPivot('point');
    }
}
