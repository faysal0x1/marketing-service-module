<?php

namespace App\Modules\MarketingService\Repositories;

use App\Modules\MarketingService\Models\MarketingService;
use App\Repositories\BaseRepository;

class MarketingServiceRepository extends BaseRepository
{
    public function __construct(MarketingService $model)
    {
        parent::__construct($model);
    }

    protected function getSearchableFields(): array
    {
        return ['name', 'created_at'];
    }

    protected function getSortableFields(): array
    {
        return ['name', 'slug', 'description', 'is_active'];
    }
}

