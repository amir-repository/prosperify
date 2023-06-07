<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vault extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'city_id'];

    public function city()
    {
        $this->belongsTo(City::class);
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class);
    }
}
