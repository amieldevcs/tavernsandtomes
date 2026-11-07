<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    use HasUlids;

    protected $primaryKey = 'session_id';

    protected $fillable = [
        'session_number',
        'session_date',
        'location_id',
        'notes',
        'session_summary',
    ];

    protected function casts(): array
    {
        return [
            'session_number' => 'integer',
            'session_date' => 'date',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'campaign_id');
    }
}
