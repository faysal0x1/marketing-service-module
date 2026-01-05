<?php

declare(strict_types=1);

namespace App\Modules\MarketingService\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingCredential extends Model
{
    protected $fillable = [
        'marketing_service_id',
        'key',
        'value',
    ];

    /**
     * Get the marketing service this credential belongs to
     */
    public function marketingService(): BelongsTo
    {
        return $this->belongsTo(MarketingService::class);
    }
}

