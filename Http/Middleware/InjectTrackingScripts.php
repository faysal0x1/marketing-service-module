<?php

declare(strict_types=1);

namespace App\Modules\MarketingService\Http\Middleware;

use App\Modules\MarketingService\Services\MarketingServices;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class InjectTrackingScripts
{
	protected MarketingServices $marketingService;

	protected ?Collection $scripts = null;

	public function __construct(MarketingServices $marketingService)
	{
		$this->marketingService = $marketingService;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  Closure(Request): (Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$response = $next($request);

		// Only inject scripts for HTML responses
		if (! $this->shouldInjectScripts($response)) {
			return $response;
		}

		$content = $response->getContent();

		// Load all scripts for all locations at once to prevent N+1 queries
		$this->loadAllScripts();

		// Inject head scripts
		$headScripts = $this->getScriptsForLocation('head');
		if ($headScripts && strpos($content, '</head>') !== false) {
			$content = str_replace('</head>', $headScripts.'</head>', $content);
		}

		// Inject body start scripts
		$bodyStartScripts = $this->getScriptsForLocation('body_start');
		if ($bodyStartScripts && strpos($content, '<body') !== false) {
			$content = preg_replace('/(<body[^>]*>)/', '$1'.$bodyStartScripts, $content);
		}

		// Inject body end scripts
		$bodyEndScripts = $this->getScriptsForLocation('body_end');
		if ($bodyEndScripts && strpos($content, '</body>') !== false) {
			$content = str_replace('</body>', $bodyEndScripts.'</body>', $content);
		}

		$response->setContent($content);

		return $response;
	}

	/**
	 * Check if scripts should be injected into the response
	 */
	protected function shouldInjectScripts(Response $response): bool
	{
		if (! $response->headers->has('Content-Type')) {
			return false;
		}

		$contentType = $response->headers->get('Content-Type');

		return strpos($contentType, 'text/html') !== false;
	}

	/**
	 * Load all scripts for all locations in a single database query (with caching)
	 */
	protected function loadAllScripts(): void
	{
		// Only load scripts once
		if ($this->scripts === null) {
			$this->scripts = \App\Modules\Cache\Services\SmartCacheService::remember(
				'static',
				'marketing_tracking_scripts',
				function () {
					return $this->marketingService->getScriptsForLocations([
						'head', 'body_start', 'body_end',
					]);
				}
			);
		}
	}

	/**
	 * Get all scripts for a specific location from the preloaded collection
	 */
	protected function getScriptsForLocation(string $location): string
	{
		if ($this->scripts === null) {
			$this->loadAllScripts();
		}

		$locationScripts = $this->scripts->filter(function ($script) use ($location) {
			return $script->location === $location;
		});

		return $locationScripts->pluck('script_content')->implode("\n");
	}
}

