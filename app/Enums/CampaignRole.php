<?php

namespace App\Enums;

enum CampaignRole: string
{
    case DM = 'dm';
    case Player = 'player';
    case CoDm = 'co_dm';
}
