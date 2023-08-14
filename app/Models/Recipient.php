<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use HasFactory;

    public const SUBMITTED = 1;
    public const ACCEPTED = 2;
    public const REJECTED = 3;
    public const PROSPERED = 4;

    protected $fillable = ["nik", "name", "address", 'phone', 'family_members', 'photo', 'recipient_status_id'];
}
