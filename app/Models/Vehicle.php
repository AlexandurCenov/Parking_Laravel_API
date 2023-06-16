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

    /**
     * Get vehicle category.
     *
     * @return BelongsTo
     */
    protected function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }

    /**
     * Get vehicle discount card.
     *
     * @return BelongsTo
     */
    protected function discountCard()
    {
        return $this->belongsTo(DiscountCard::class, 'discount_card_id');
    }
}
