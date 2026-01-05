# Marketing Service Module

Provides marketing services, credentials, tracking scripts, and middleware (`InjectTrackingScripts`) packaged as a Laravel module.

## Installation

```
composer require nwidart/laravel-modules
composer require joshbrw/laravel-module-installer
composer require faysal0x1/marketing-service-module
php artisan module:enable MarketingService
php artisan migrate
php artisan vendor:publish --tag=marketing-service-views --force
php artisan optimize:clear
```

## Features
- Models for marketing services, credentials, tracking scripts
- Repository + service bindings (`modules.marketing-service.repository` / `.service`)
- Middleware to inject tracking scripts into responses
- JSX pages published via `marketing-service-views`

## License
MIT
