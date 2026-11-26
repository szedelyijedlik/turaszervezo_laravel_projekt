<?php

namespace App\Models;

use Closure;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


#[Fillable(["destination", "description", "starts_on", "days", "difficulty", "price_per_person", "free_places"])]
class Tours extends Model
{
    /** @use HasFactory<\Database\Factories\ToursFactory> */
    use HasFactory;

    public static function validateStartOn(string $attribute, mixed $value, Closure $fail) : void
    {
        $validator = Validator::make(
            [$attribute => $value],
            [$attribute => [Rule::date()->afterToday()]],
        );

        if ($validator->fails())
        {
            $fail('The `:attribute` must be a date after today.');
        }
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_on' => 'date:Y-m-d',
        ];
    }

    /**
     * Get all of the participants for the Tours
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class, "tour_id", "id");
    }
}
