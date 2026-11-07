<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Resources\CampaignResource;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\DB;
use Enums\CampaignRole;
use Illuminate\Auth\Access\Gate;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return CampaignResource::collection(
            $request->user()->campaigns()->withCount('members')->orderBy('campaign_name')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampaignRequest $request)
    {
        $campaign = DB::transaction( function () use ($request) {
            // returns only the fields that passed the rules.
            $campaign = new Campaign($request->validated());

            // Backend checks to tell who the logged in user is
            $campaign->dungeon_master_id = $request->user()->id;
            $campaign->save();

            // Also add the DM too the members pivot, so $user->campaigns find every campaign they belong to with one query
            $campaign->members()->attach($request->user()->id, ['role' => CampaignRole::Dm->value]);

            return $campaign;
        });

        return (new CampaignResource($campaign))->response()->setStatusCode(201);
    }

    /**
     * GET /campaigns/{campaign} show one campaign
     * Laravel's "route model binding" 
     */
    public function show(Campaign $campaign)
    {
        // Throws 403 automatically if the policy's view() return false
        Gate::authorize('view', $campaign);
        return new CampaignResource($campaign->loadCount('members'));
    }

    /**
     * PUT /campaigns/{campaign}: edit a campaign
     */
    public function update(StoreCampaignRequest $request, Campaign $campaign)
    {
        Gate::authorize('update', $campaign);
        $campaign->update($request->validated());;
        return new CampaignResource($campaign);
    }

    /**
     * Delete /campaigns/{campaign}
     */
    public function destroy(Campaign $campaign)
    {
        Gate::authorize('delete', $campaign);
        $campaign->delete();            // soft delete: row stays, deleted_at is set
        return response()->noContent(); // 204: success, nothing to send back
    }
}
