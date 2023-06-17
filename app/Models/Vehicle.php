<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_number',
        'category_id',
        'card_id',
    ];

    public $timestamps = false;

    /**
     * Get vehicle category.
     *
     * @return HasOne
     */
    public function category()
{
    return $this->belongsTo(Category::class);
}

    /**
     * Get vehicle discount card.
     *
     * @return HasOne
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
