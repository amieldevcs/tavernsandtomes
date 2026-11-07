<?php

namespace App\Http\Requests;

use App\Models\Campaign;
use App\Models\GameSession;

final class UpdateGameSessionRequest extends GameSessionRequest
{
    // Route: PUT|PATCH /sessions/{game_session}. The URL only contains the
    // session, so we reach the campaign through it.
    protected function campaign(): Campaign
    {
        return $this->session()->campaign;
    }

    protected function ignoredSessionId(): ?string
    {
        return (string) $this->session()->getKey();
    }

    public function session(): GameSession
    {
        return $this->route('game_session');
    }
}
