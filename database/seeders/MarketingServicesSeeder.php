<?php

declare(strict_types=1);

namespace App\Modules\MarketingService\database\seeders;

use App\Modules\MarketingService\Models\MarketingService;
use Illuminate\Database\Seeder;

class MarketingServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Google Analytics',
                'slug' => 'google-analytics',
                'description' => 'Track website traffic and user behavior',
            ],
            [
                'name' => 'Google Search Console',
                'slug' => 'google-search-console',
                'description' => 'Monitor and maintain your site\'s presence in Google Search results',
            ],
            [
                'name' => 'Facebook Pixel',
                'slug' => 'facebook-pixel',
                'description' => 'Track conversions from Facebook ads and build audiences',
            ],
            [
                'name' => 'Google Tag Manager',
                'slug' => 'google-tag-manager',
                'description' => 'Manage all your marketing tags without editing code',
            ],
            [
                'name' => 'Bing Webmaster Tools',
                'slug' => 'bing-webmaster',
                'description' => 'Monitor your website\'s presence in Bing search results',
            ],
            [
                'name' => 'Microsoft Clarity',
                'slug' => 'microsoft-clarity',
                'description' => 'Understand how users interact with your website through heatmaps and session recording',
            ],
            [
                'name' => 'Pinterest Tag',
                'slug' => 'pinterest-tag',
                'description' => 'Track conversions from Pinterest ads',
            ],
            [
                'name' => 'TikTok Pixel',
                'slug' => 'tiktok-pixel',
                'description' => 'Track conversions from TikTok ads',
            ],
            [
                'name' => 'Twitter Pixel',
                'slug' => 'twitter-pixel',
                'description' => 'Track conversions from Twitter ads',
            ],
            [
                'name' => 'LinkedIn Insight Tag',
                'slug' => 'linkedin-insight',
                'description' => 'Track conversions from LinkedIn ads',
            ],
        ];

        foreach ($services as $service) {
            MarketingService::updateOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }
    }
}
