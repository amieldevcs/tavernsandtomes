<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGameSessionRequest;
use App\Models\GameSession;
use Illuminate\Http\Request;

class GameSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Campaign $campaign)
    {
        Gate::authorize('view', $campaign);

        // with("campaign') looads the campaign once for all sessions. The resource calls $this->campaign->isDm(),
        // and without this it wouold run one extra query per session
        return GameSessionResource::collection($campaign->game_sessions()->with('campaign'))->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameSessionRequest $request, Campaign $campaign)
    {
        Gate::authorize('manageSessions', $campaign);

        $data = $request->validated();

        // $data['session_number'] ??=
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
