<?php

namespace App\Http\Requests;

use GameSessionRequest;

class StoreGameSessionRequest extends GameSessionRequest
{
    // Route: POST /campaigns/{campaign}/sessions. Laravel has already
    // turned {campaign} into a Campaign model (route model binding).
    protected function campaign(): Campaign
    {
        return $this->route('campaign');
    }

    protected function ignoredSessionId(): ?string
    {
        return null; // nothing to ignore when creating
    }
}
