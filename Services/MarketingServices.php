<?php

declare(strict_types=1);

namespace App\Modules\MarketingService\Services;

use App\Modules\MarketingService\Models\MarketingService;
use App\Modules\MarketingService\Models\TrackingScript;
use Illuminate\Database\Eloquent\Collection;

class MarketingServices
{
    /**
     * Get all active marketing services
     */
    public function getActiveServices(): Collection
    {
        return MarketingService::where('is_active', true)
            ->with('credentials')->get();
    }

    /**
     * Get a marketing service by slug
     */
    public function getServiceBySlug(string $slug): ?MarketingService
    {
        return MarketingService::where('slug', $slug)
            ->with(['credentials', 'trackingScripts'])
            ->first();
    }

    /**
     * Update marketing service credentials
     */
    public function updateServiceCredentials(string $slug, array $credentials): bool
    {
        $service = $this->getServiceBySlug($slug);

        if (! $service) {
            return false;
        }

        foreach ($credentials as $key => $value) {
            $service->setCredential($key, $value);
        }

        return true;
    }

    /**
     * Update a marketing service's tracking script
     */
    public function updateTrackingScript(string $slug, string $location, string $script): bool
    {
        $service = $this->getServiceBySlug($slug);

        if (! $service) {
            return false;
        }

        TrackingScript::updateOrCreate(
            [
                'marketing_service_id' => $service->id,
                'location' => $location,
            ],
            [
                'script_content' => $script,
                'is_active' => true,
            ]
        );

        // Clear cache to ensure updated scripts are shown immediately
        \App\Modules\Cache\Services\SmartCacheService::forget('static', 'marketing_tracking_scripts');

        return true;
    }

    /**
     * Toggle the active status of a marketing service
     */
    public function toggleServiceStatus(string $slug, bool $status): bool
    {
        $service = $this->getServiceBySlug($slug);

        if (! $service) {
            return false;
        }

        $service->is_active = $status;
        $service->save();

        // Clear cache to ensure status changes are reflected immediately
        \App\Modules\Cache\Services\SmartCacheService::forget('static', 'marketing_tracking_scripts');

        return true;
    }

    /**
     * Get all tracking scripts for a specific location
     *
     * This method has been optimized to prevent N+1 query issues
     */
    public function getScriptsForLocation(string $location): Collection
    {
        return TrackingScript::active()
            ->forLocation($location)
            ->whereHas('marketingService', function ($query) {
                $query->where('is_active', true);
            })
            ->with(['marketingService', 'marketingService.credentials'])
            ->get();
    }

    /**
     * Get all tracking scripts for multiple locations at once
     *
     * This method efficiently loads all needed scripts in a single query
     */
    public function getScriptsForLocations(array $locations): Collection
    {
        return TrackingScript::active()
            ->whereIn('location', $locations)
            ->whereHas('marketingService', function ($query) {
                $query->where('is_active', true);
            })
            ->with(['marketingService', 'marketingService.credentials'])
            ->get();
    }

    /**
     * Generate tracking code for Google Analytics
     */
    public function generateGoogleAnalyticsScript(string $measurementId): string
    {
        return "
			<!-- Google Analytics -->
			<script async src=\"https://www.googletagmanager.com/gtag/js?id={$measurementId}\"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());
			  gtag('config', '{$measurementId}');
			</script>
		";
    }

    /**
     * Generate tracking code for Facebook Pixel
     */
    public function generateFacebookPixelScript(string $pixelId): string
    {
        return "
			<!-- Facebook Pixel Code -->
			<script>
			  !function(f,b,e,v,n,t,s)
			  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			  n.queue=[];t=b.createElement(e);t.async=!0;
			  t.src=v;s=b.getElementsByTagName(e)[0];
			  s.parentNode.insertBefore(t,s)}(window, document,'script',
			  'https://connect.facebook.net/en_US/fbevents.js');
			  fbq('init', '{$pixelId}');
			  fbq('track', 'PageView');
			</script>
			<noscript>
			  <img height=\"1\" width=\"1\" style=\"display:none\"
				   src=\"https://www.facebook.com/tr?id={$pixelId}&ev=PageView&noscript=1\"  alt=\"sd\"/>
			</noscript>
			<!-- End Facebook Pixel Code -->
			";
    }
}

