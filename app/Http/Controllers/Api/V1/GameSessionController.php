<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGameSessionRequest;
use App\Http\Requests\UpdateGameSessionRequest;
use App\Http\Resources\GameSessionResource;
use App\Models\Campaign;
use App\Models\GameSession;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class GameSessionController extends Controller
{
    /**
     * GET /campaigns/{campaign}/sessions
     */
    public function index(Campaign $campaign): AnonymousResourceCollection
    {
        Gate::authorize('view', $campaign);

        $sessions = $campaign->sessions()->orderBy('session_number')->get();
        // with("campaign') looads the campaign once for all sessions. The resource calls $this->campaign->isDm(),
        // and without this it wouold run one extra query per session
        $sessions->each(
            fn (GameSession $session) => $session->setRelation('campaign', $campaign)
        );

        return GameSessionResource::collection($sessions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameSessionRequest $request, Campaign $campaign): JsonResponse
    {
        $data = $this->writableFields($request->validated(), $campaign);

        try {
            $session = DB::transaction(function () use ($campaign, $data): GameSession {
                // Lock this campaign's row until the transaction ends, so two
                // simultaneous requests can't both calculate the same "next"
                // session number.
                Campaign::query()->whereKey($campaign->getKey())->lockForUpdate()->first();

                if (! isset($data['session_number'])) {
                    // Query GameSession directly (not through the relation) so
                    // no ordering interferes with the aggregate.
                    $data['session_number'] = (int) GameSession::query()
                        ->where('campaign_id', $campaign->getKey())
                        ->max('session_number') + 1;
                }

                // Creating through the relation fills in campaign_id for us.
                return $campaign->sessions()->create($data);
            });
        } catch (UniqueConstraintViolationException) {
            // Last line of defence: the database's unique index caught a
            // duplicate that slipped past validation (a rare race).
            throw $this->sessionNumberTaken();
        }

        $session->setRelation('campaign', $campaign);

        // 201 = "Created", the correct status for a successful POST.
        return (new GameSessionResource($session))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * GET /sessions/{game_session}
     */
    public function show(GameSession $gameSession): GameSessionResource
    {
        // Loading the relation here also caches it on the model, so the
        // resource reuses it instead of querying again.
        Gate::authorize('view', $gameSession->campaign);

        return new GameSessionResource($gameSession);
    }

    /**
     * PUT|PATCH /sessions/{game_session}
     *
     * Authorization already happened in UpdateGameSessionRequest.
     */
    public function update(UpdateGameSessionRequest $request, GameSession $gameSession): GameSessionResource
    {
        $data = $this->writableFields($request->validated(), $gameSession->campaign);

        try {
            $gameSession->update($data);
        } catch (UniqueConstraintViolationException) {
            throw $this->sessionNumberTaken();
        }

        return new GameSessionResource($gameSession);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameSession $gameSession): Response
    {
        Gate::authorize('deleteSessions', $gameSession->campaign);

        $gameSession->delete();

        // 204 = success, nothing to send back.
        return response()->noContent();
    }

    /**
     * Authorization isn't only "may you do this?" but also "which fields may
     * you touch?". A Story Writer who sends dm_notes anyway just has that
     * field silently dropped.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function writableFields(array $validated, Campaign $campaign): array
    {
        return Gate::allows('viewSecrets', $campaign)
            ? $validated
            : Arr::except($validated, ['dm_notes']);
    }

    // Builds the same kind of 422 error that normal validation produces,
    // so the frontend handles it the same way.
    private function sessionNumberTaken(): ValidationException
    {
        return ValidationException::withMessages([
            'session_number' => 'That session number is already used in this campaign.',
        ]);
    }
}
