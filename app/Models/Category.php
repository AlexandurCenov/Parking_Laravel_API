<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Category has many vehicles
     *
     * @return BelongsTo
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
