<?php

namespace App\Enums;

enum CampaignRole: string
{
    case Dm = 'dm';
    case Player = 'player';
    case CoDm = 'co_dm';

    public function isGameMaster(): bool
    {
        return in_array($this, [self::Dm, self::CoDm], true);
    }
}
