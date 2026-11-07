<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasUlids, SoftDeletes;

    protected $primaryKey = 'campaign_id';

    protected $fillable = [
        'campaign_name',
        'ruleset',
        'campagin_start_date',
        'campaign_end_date',
    ];

    // Tiny per-request cache.
    private array $membershipCache = [];

    protected function casts(): array
    {
        return [
            'campaign_start_date' => 'date',
            'campaign_end_date' => 'date',
        ];
    }

    /**
     * Dungeon Master Relationship
     */
    public function dungeonMaster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_dungeon_master');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Members Relationship
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'campaign_members', 'campaign_id', 'user_id')
            ->withPivot('role', 'is_admin', 'is_story_writer')
            ->withTimestamps();
    }

    // Returns the user's pivot row (their role and flags), or null if they
    // aren't a member. array_key_exists is used instead of isset() because
    // null ("not a member") is a legitimate answer we also want to cache.
    public function membershipFor(User $user): ?Pivot
    {
        $userId = (int) $user->getKey();

        // array_key_exists (not isset) because null is a valid cached answer:
        // "we checked, this user is not a member".
        if (! array_key_exists($userId, $this->membershipCache)) {
            $member = $this->members()->where('users.id', $userId)->first();

            // Members loaded through a pivot relation carry a ->pivot property.
            $this->membershipCache[$userId] = $member?->pivot;
        }

        return $this->membershipCache[$userId];
    }

    // Call this after changing someone's role within the same request,
    // otherwise the cache would keep returning the old answer.
    public function forgetMembershipCache(): void
    {
        $this->membershipCache = [];
    }

    public function isMember(User $user): bool
    {
        return $this->membershipFor($user) !== null;
    }

    public function isAdmin(User $user): bool
    {
        return $this->membershipFor($user)?->is_admin;
    }

    public function isStoryWriter(User $user): bool
    {
        return (bool) $this->membershipFor($user)?->is_story_writer;
    }

    /**
     * Session Relationships
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(GameSessions::class, 'campaign_id', 'campaign_id')
            ->orderBy('session_number');
    }

    /**
     * Check if the user is the DM of the Campaign
     */
    public function isDM(User $user): bool
    {
        return (int) $this->assigned_dungeon_master === (int) $user->id;
    }
}
