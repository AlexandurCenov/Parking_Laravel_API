<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Get vehicle discount card.
     *
     * @return BelongsTo
     */
    protected function vehicles()
    {
        return $this->hasMany(Vehicle::class,'category_id', 'id');
    }
}
