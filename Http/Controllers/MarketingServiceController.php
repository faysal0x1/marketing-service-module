<?php

declare(strict_types=1);

namespace App\Modules\MarketingService\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MarketingService\Models\MarketingService;
use App\Modules\MarketingService\Services\MarketingServices as MarketingServiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MarketingServiceController extends Controller
{
    protected $marketingService;

    public function __construct(MarketingServiceService $marketingService)
    {
        $this->marketingService = $marketingService;
    }

    /**
     * Display a listing of marketing services
     */
    public function index(): Response
    {
        // Eager load credentials to prevent N+1 query issue
        $services = MarketingService::with('credentials')->get();

        return Inertia::render('admin/marketing/Index', [
            'services' => $services,
        ]);
    }

    /**
     * Show the form for editing a marketing service
     */
    public function edit(string $slug)
    {
        // Use eager loading in the getServiceBySlug method
        $service = MarketingService::where('slug', $slug)
            ->with(['credentials', 'trackingScripts'])
            ->first();

        if (! $service) {
            return redirect()->route('marketing-service.index')
                ->with('error', 'Marketing service not found');
        }

        return Inertia::render('admin/marketing/Edit', [
            'service' => $service,
        ]);
    }

    /**
     * Update marketing service credentials
     */
    public function updateCredentials(Request $request, string $slug): RedirectResponse
    {
        $service = $this->marketingService->getServiceBySlug($slug);

        if (! $service) {
            return redirect()->route('marketing-service.index')
                ->with('error', 'Marketing service not found');
        }

        $credentials = $request->validate([
            'credentials' => 'required|array',
            'credentials.*' => 'required|string',
        ])['credentials'];

        $this->marketingService->updateServiceCredentials($slug, $credentials);

        // Generate and update tracking scripts
        $this->generateTrackingScripts($service, $credentials);

        return redirect()->route('marketing-service.edit', $slug)
            ->with('success', 'Marketing service credentials updated successfully');
    }

    /**
     * Toggle the active status of a marketing service
     */
    public function toggleStatus(string $slug): RedirectResponse
    {
        $service = $this->marketingService->getServiceBySlug($slug);

        if (! $service) {
            return redirect()->route('marketing-service.index')
                ->with('error', 'Marketing service not found');
        }

        $this->marketingService->toggleServiceStatus($slug, ! $service->is_active);

        return redirect()->back()
            ->with('success', 'Marketing service status updated successfully');
    }

    /**
     * Generate tracking scripts based on service type and credentials
     */
    protected function generateTrackingScripts(MarketingService $service, array $credentials): void
    {
        switch ($service->slug) {
            case 'google-analytics':
                if (isset($credentials['measurement_id'])) {
                    $script = $this->marketingService->generateGoogleAnalyticsScript($credentials['measurement_id']);
                    $this->marketingService->updateTrackingScript($service->slug, 'head', $script);
                }
                break;

            case 'facebook-pixel':
                if (isset($credentials['pixel_id'])) {
                    $script = $this->marketingService->generateFacebookPixelScript($credentials['pixel_id']);
                    $this->marketingService->updateTrackingScript($service->slug, 'head', $script);
                }
                break;

            case 'google-tag-manager':
                if (isset($credentials['container_id'])) {
                    $headScript = "
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{$credentials['container_id']}');</script>
<!-- End Google Tag Manager -->
";
                    $bodyScript = "
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id={$credentials['container_id']}\"
height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
";
                    $this->marketingService->updateTrackingScript($service->slug, 'head', $headScript);
                    $this->marketingService->updateTrackingScript($service->slug, 'body_start', $bodyScript);
                }
                break;

            default:
                // For custom script services
                if (isset($credentials['script'])) {
                    $location = $credentials['location'] ?? 'head';
                    $this->marketingService->updateTrackingScript($service->slug, $location, $credentials['script']);
                }
                break;
        }
    }
}

