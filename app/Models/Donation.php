<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status', 'donation_date', 'recipient_id'];

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class)->withPivot('id', 'outbound_plan', 'outbound_result', 'food_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('id');
    }
}
