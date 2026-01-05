<?php

namespace App\Modules\MarketingService\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingScript extends Model
{
    use HasFactory;

    protected $table = 'tracking_scripts';

    protected $fillable = [
        'marketing_service_id',
        'location',
        'script_content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function marketingService(): BelongsTo
    {
        return $this->belongsTo(MarketingService::class, 'marketing_service_id');
    }

    // Scopes
    /**
     * Scope a query to only include active tracking scripts.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by location.
     */
    public function scopeForLocation(Builder $query, string $location): Builder
    {
        return $query->where('location', $location);
    }
}

