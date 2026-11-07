<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'campaign_id' => $this->campaign_id,
            'campaign_name' => $this->campaign_name,
            'ruleset' => $this->ruleset,
            'campaign_start_date' => $this->campaign_start_date?->toDateString(),
            'campaign_end_date' => $this->campaign_end_date?->toDateString(),
            'is_dm' => $this->isDm($request->user()),
            'members_count' => $this->whenCounted('members'),
        ];
    }
}
