<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodVault extends Model
{
    use HasFactory;

    protected $table = 'food_vault';

    protected $fillable = ["food_id", "vault_id"];

    public function vault()
    {
        return $this->BelongsTo(Vault::class);
    }
}
