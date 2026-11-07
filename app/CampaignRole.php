<?php

namespace App;

enum CampaignRole: string
{
    case DM = 'dm';
    case Player = 'player';
    case CoDm = 'co-dm';
}
