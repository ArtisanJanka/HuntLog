<?php

namespace App\Models;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Polygon extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'coordinates', 'user_id', 'group_id'];

    /**
     * Always read 'coordinates' as array.
     * Expected shape: [ ['lat'=>.., 'lng'=>..], ... ]
     */
    protected $casts = [
        'coordinates' => 'array',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────
    /**
     * Visible to a user if:
     *  - they own it, or
     *  - it belongs to any of their groups.
     */
    public function scopeVisibleTo($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhereIn('group_id', $user->groups()->select('groups.id'));
        });
    }

    public function scopeOwnedBy($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeInUserGroups($query, User $user)
    {
        return $query->whereIn('group_id', $user->groups()->select('groups.id'));
    }

    // ── Mutators / Accessors ───────────────────────────────────────────────────
    /**
     * Normalize coordinates on write. Accepts:
     *  - array (stored as JSON)
     *  - JSON string
     *  - double-encoded JSON string
     */
    public function setCoordinatesAttribute($value): void
    {
        // If already array-like, trust it.
        if (is_array($value)) {
            $this->attributes['coordinates'] = json_encode($value, JSON_UNESCAPED_UNICODE);
            return;
        }

        // If a string, try to decode; if double-encoded, decode twice.
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            if (is_array($decoded)) {
                $this->attributes['coordinates'] = json_encode($decoded, JSON_UNESCAPED_UNICODE);
                return;
            }
        }

        // Fallback: empty array
        $this->attributes['coordinates'] = json_encode([], JSON_UNESCAPED_UNICODE);
    }

    // ── Helpers ────────────────────────────────────────────────────────────────
    /** Count of points. */
    public function getPointsCountAttribute(): int
    {
        $coords = $this->coordinates ?? [];
        return is_array($coords) ? count($coords) : 0;
    }

    /**
     * Return closed ring as [lng, lat] pairs (GeoJSON order).
     * Input is expected as array of ['lat'=>..,'lng'=>..].
     */
    public function ringLngLat(): array
    {
        $coords = $this->coordinates ?? [];
        if (!is_array($coords) || count($coords) < 3) {
            return [];
        }

        $ring = array_map(function ($p) {
            $lat = isset($p['lat']) ? (float) $p['lat'] : null;
            $lng = isset($p['lng']) ? (float) $p['lng'] : null;
            return [$lng, $lat];
        }, $coords);

        if ($ring
            && ($ring[0][0] !== $ring[count($ring) - 1][0]
             || $ring[0][1] !== $ring[count($ring) - 1][1])) {
            $ring[] = $ring[0];
        }

        return $ring;
    }

    /** Minimal GeoJSON FeatureCollection for this polygon. */
    public function toGeoJson(?string $nameOverride = null): ?array
    {
        $ring = $this->ringLngLat();
        if (count($ring) < 4) {
            return null; // need 3 points + closed ring
        }

        return [
            'type' => 'FeatureCollection',
            'features' => [[
                'type' => 'Feature',
                'properties' => [
                    'id'   => $this->id,
                    'name' => $nameOverride ?? $this->name,
                ],
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [ $ring ],
                ],
            ]],
        ];
    }

    /** Convenience check: is this polygon visible to $user? */
    public function isVisibleTo(User $user): bool
    {
        if ($this->user_id === $user->id) return true;
        if (!$this->group_id) return false;

        return $user->groups()->whereKey($this->group_id)->exists();
    }
}
