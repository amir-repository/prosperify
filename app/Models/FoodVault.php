<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodVault extends Model
{
    use HasFactory;

    protected $table = 'food_vault';

    protected $fillable = ["food_id", "vault_id"];
}
