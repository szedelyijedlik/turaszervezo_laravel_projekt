<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Http\Resources\ParticipantResource;
use App\Models\Participant;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $participants = Participant::with('tour')->latest()->paginate();

        return ParticipantResource::collection($participants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParticipantRequest $request)
    {
        $validated = $request->validated();

        return Participant::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant)
    {
        return ParticipantResource::make($participant->load('tour'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParticipantRequest $request, Participant $participant)
    {
        $participant->update($request->validated());

        $participant->load('tour');

        return ParticipantResource::make($participant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        $participant->delete();

        return response()->noContent();
    }
}
