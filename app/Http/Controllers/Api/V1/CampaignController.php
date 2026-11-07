<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Enums\CampaignRole;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $campaign = DB::transaction(function () use ($request) {

            // safe()->except() drops creator_role from the data, sicne it isn't a column on the campaigns table
            // and shouldn't go into the model.
            $campaign = new Campaign($request->safe()->except('creator_role'));
            $campaign->created_by = $request->user()->id;
            $campaign->save();

            $campaign->members()->attach($request->user()->id, [
                'role' => $request->input('creator_role', CampaignRole::Dm->value),
                'is_admin' => true, // whoever sets it up is the admin.
            ]);

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
        $campaign->update($request->validated());

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
