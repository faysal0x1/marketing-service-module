<?php

declare(strict_types=1);

namespace App\Modules\MarketingService\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingService extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all the credentials for this marketing service
     */
    public function credentials(): HasMany
    {
        return $this->hasMany(MarketingCredential::class);
    }

    /**
     * Get all the tracking scripts for this marketing service
     */
    public function trackingScripts(): HasMany
    {
        return $this->hasMany(TrackingScript::class);
    }

    /**
     * Get a credential value by key
     */
    public function getCredential(string $key): ?string
    {
        $credential = $this->credentials()->where('key', $key)->first();

        return $credential ? $credential->value : null;
    }

    /**
     * Set a credential value
     */
    public function setCredential(string $key, string $value): self
    {
        $this->credentials()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        return $this;
    }
}

