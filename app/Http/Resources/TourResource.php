<?php

namespace App\Http\Resources;

use App\Enums\TourDifficulty;
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
            'id' => $this->id,
            "destination" => $this->destination,
            "description" => $this->description,
            "starts_on" => $this->starts_on->format("Y-m-d"),
            "days" => $this->days,
            "ends_on" => $this->starts_on->addDays($this->days)->format("Y-m-d"),
            "difficulty" => $this->difficulty,
            "difficulty_level" => TourDifficulty::from($this->difficulty)->level(),
            "price_per_person" => $this->price_per_person,
            "free_places" => $this->free_places,
            "full" => $this->free_places == 0,
            'participants_count' => $this->whenCounted('participants'),
            "participants" => ParticipantResource::collection(
                $this->whenLoaded('participants')
            ),
        ];
    }
}
