<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'detail', 'expired_date', 'amount', 'unit', 'stored_timestamp', 'user_id', 'category_id', 'sub_category_id'];

    public function vaults()
    {
        return $this->belongsToMany(Vault::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function donations()
    {
        return $this->belongsToMany(Donation::class)->withPivot('id', 'outbound_plan', 'outbound_result', 'food_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
