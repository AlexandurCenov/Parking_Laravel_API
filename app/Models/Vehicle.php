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
        'vehicle_category_id',
        'discount_card_id',
    ];

    public $timestamps = false;

    /**
     * Get vehicle category.
     *
     * @return HasOne
     */
    protected function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'id', 'category_id');
    }

    /**
     * Get vehicle discount card.
     *
     * @return HasOne
     */
    protected function discountCard()
    {
        return $this->hasOne(DiscountCard::class, 'id', 'card_id');
    }
}
