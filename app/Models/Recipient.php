<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use HasFactory;

    protected $fillable = ["name", "address", "phone", 'family_member', 'document', 'status'];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
