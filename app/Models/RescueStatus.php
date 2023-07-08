<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RescueStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function rescues()
    {
        return $this->hasMany(Rescue::class);
    }

    public function rescueUser()
    {
        return $this->hasMany(RescueUser::class);
    }
}
