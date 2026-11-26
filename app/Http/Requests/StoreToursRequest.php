<?php

namespace App\Http\Requests;

use App\Enums\TourDifficulty;
use App\Models\Tours;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


use const App\Http\Controllers\DEBUG;

class StoreToursRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return DEBUG ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "destination" => "required|string|max:100",
            "description" => "sometimes|string|max:1000",
            "starts_on" => ['required', 'date', Tours::validateStartOn(...)],
            "days" => "required|integer|between:1,14",
            "difficulty" => ["required", Rule::in(TourDifficulty::cases())],
            "price_per_person" => "required|integer|min:0",
            "free_places" => "sometimes|integer|between:0,60"
        ];
    }
}
