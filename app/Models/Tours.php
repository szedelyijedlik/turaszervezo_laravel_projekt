<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(["destination", "description", "starts_on", "days", "difficulty", "price_per_person", "free_places"])]
class Tours extends Model
{
    /** @use HasFactory<\Database\Factories\ToursFactory> */
    use HasFactory;

    /**
     * Get all of the participants for the Tours
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
