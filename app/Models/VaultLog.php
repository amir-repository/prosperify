<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaultLog extends Model
{
    use HasFactory;

    protected $fillable = ['vault_id', 'vault_name', 'food_rescue_log_id'];
}
