<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rescue extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_name',
        'pickup_address',
        'phone',
        'email',
        'title',
        'description',
        'rescue_date',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
