<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasUlids, SoftDeletes;

    protected $primaryKey = 'campaign_id';

    protected $fillable = ['campaign_name', 'ruleset', 'campagin_start_date', 'campaign_end_date'];\

    protected function casts(): array {
        return [
            'campaign_start_date' => 'date',
            'campaign_end_date' => 'date'
        ];
    }

    /**
     * Dungeon Master Relationship
     */
    public  function dungeonMaster(): BelongsTo {
        return $this->belongsTo(User::class, 'dungeon_master_id');
    }

    /**
     * Members Relationship
     */
    public  function members(): BelongsToMany {
        return $this->belongsToMany(User::class, 'campaign_members', 'campaign_id', 'user_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Session Relationships
     */
    public function sessions(): HasMany {
        return $this->hasMany(GameSessions::class, 'campaign_id', 'campaign_id')
                    ->orderBy('session_number');
    }

    /**
     * Check if the user is the DM of the Campaign
     */
    public function isDM(User $user): bool {
        return (int) $this->dungeon_master_id === (int) $user->id;
    }
}
