<?php

namespace App\Http\Controllers;

use App\Enums\TourDifficulty;
use App\Models\Tours;
use App\Http\Requests\StoreToursRequest;
use App\Http\Requests\UpdateToursRequest;
use App\Http\Resources\ParticipantResource;
use App\Http\Resources\TourResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

const DEFAULT_PER_PAGE = 15;
const DEBUG = true;

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
            "max_days" => "integer|min:1",
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

                    case 'max_days':
                        $tourQuery->where('days', "<=", $filter);
                        break;

                    case 'available':
                        if ($filter) {
                            $tourQuery->where('free_places', '>', 0);
                        }
                        break;
                }
            }
        }

        if ($sort = $request->input("sort")) {
            $sort_by_direction = function (string $value) use ($tourQuery) {
                $direction = "asc";
                if (str_contains($value, "-")) {
                    $direction = "desc";
                    $value = substr($value, 1);
                }

                $tourQuery->orderBy($value, $direction);
            };

            $sort_by_direction($sort);
        } else {
            $tourQuery->orderBy("starts_on", "asc");
        }

        $tourQuery->withCount("participants");

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
    public function show(Tours $tour)
    {
        return TourResource::make($tour->load("participants"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateToursRequest $request, Tours $tour)
    {
        $tour->update($request->validated());

        return TourResource::make($tour->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $tour = Tours::find($id);

        if (!$tour) {
            return response()->json([
                "message" => "The requested resource was not found. (id: $id)"
            ], 404);
        }

        if ($tour->participants()->count() > 0)
            return response()->json([
                "message" => "Cannot delete a tour with active participants"
            ], 409);

        $tour->delete();

        return response()->noContent();
    }

    public function participants(Tours $tour, Request $request)
    {
        $request->validate([
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $per_page = $request->input("per_page", DEFAULT_PER_PAGE);

        return ParticipantResource::collection(
            $tour->participants()
                ->latest()
                ->paginate($per_page)
                ->withQueryString()
        );
    }

    public function calculate_revenue(Tours $tour)
    {
        $sum_headcount = $tour->participants()->sum("persons");
        $calulated_revanue = round($tour->price_per_person * $sum_headcount, 0);

        return response()->json([
            "sum_headcount" => $sum_headcount,
            "calulated_revanue" => $calulated_revanue
        ]);
    }
}
