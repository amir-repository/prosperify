<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipientLogNote extends Model
{
    use HasFactory;

    protected $fillable = ['note', 'recipient_log_id'];

    public function recipientLog()
    {
        return $this->belongsTo(RecipientLog::class);
    }
}
