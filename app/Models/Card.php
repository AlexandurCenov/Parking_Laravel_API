<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    
    /**
     * Card has many vehicles
     *
     * @return BelongsTo
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
