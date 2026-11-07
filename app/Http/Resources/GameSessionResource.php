<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'session_id' => $this->session_id,
            'campaign_id' => $this->campaign_id,
            'session_number' => $this->session_number,
            'session_date' => $this->session_date->toDateString(),
            'location_id' => $this->location_id,
            'npcs' => $this->npcs,
            'session_summary' => $this->session_summary,
            'ai_summary' => $this->ai_summary,
            'notes' => $this->notes,
        ];
    }
}
