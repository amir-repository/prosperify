<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodRescue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'food_rescue';

    protected $fillable = ['user_id', 'rescue_id', 'food_rescue_status_id', 'rescue_user_id'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function rescue()
    {
        return $this->belongsTo(Rescue::class);
    }
}
