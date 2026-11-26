<?php

namespace App\Http\Controllers;

use App\Enums\TourDifficulty;
use App\Models\Tours;
use App\Http\Requests\StoreToursRequest;
use App\Http\Requests\UpdateToursRequest;
use App\Http\Resources\TourResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

const DEFAULT_PER_PAGE = 15;

class ToursController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        $validationRules = [
            "search" => "string|max:100",
            "difficulty" => ["string", Rule::in(TourDifficulty::cases()), "max:10"],
            "from" => "date",
            "max_price" => "integer|min:0",
            "available" => "bool",
            "sort" => Rule::in([
                "id",
                "starts_on",
                "days",
                "price_per_person",
                "-id",
                "-starts_on",
                "-days",
                "-price_per_person"
            ]),
            "per_page" => "integer|min:1|max:100"
        ];

        $request->validate($validationRules);

        $tourQuery = Tours::query();

        foreach (array_keys($validationRules) as $key) {
            if ($filter = $request->input($key)) {
                switch ($key) {
                    case 'search':
                        $tourQuery->where('destination', 'like', "%{$filter}%");
                        break;

                    case 'difficulty':
                        $tourQuery->where('difficulty', $filter);
                        break;

                    case 'from':
                        $tourQuery->whereDate('starts_on', '>=', $filter);
                        break;

                    case 'max_price':
                        $tourQuery->where('price_per_person', '<=', $filter);
                        break;

                    case 'available':
                        if ($filter) {
                            $tourQuery->where('free_places', '>', 0);
                        }
                        break;
                }
            }
        }

        if ($sort_by = $request->input("sort")) {
            $sort_by_direction = function (string $value) use ($tourQuery) {
                $direction = "asc";
                if (str_contains($value, "-")) {
                    $direction = "desc";
                    $value = substr($value, 1);
                }

                $tourQuery->orderBy($value, $direction);
            };

            $sort_by_direction($sort_by);
        } else {
            $tourQuery->orderBy("starts_on", "asc");
        }

        $per_page = $request->input("per_page", DEFAULT_PER_PAGE);
        return TourResource::collection(
            $tourQuery->paginate($per_page)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreToursRequest $request)
    {
        $validated = $request->validated();

        return TourResource::make(
            Tours::create($validated)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Tours $tours)
    {
        return TourResource::make($tours);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateToursRequest $request, Tours $tours)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tours $tours)
    {
        //
    }
}
