<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "destination" => $this->destination,
            "description" => $this->description,
            "starts_on" => $this->starts_on,
            "days" => $this->days,
            "difficulty" => $this->difficulty,
            "price_per_person" => $this->price_per_person,
            "free_places" => $this->free_places,
            "full" => $this->free_places > 0,
            "participants_count" => $this->participants->count()
        ];
    }
}
