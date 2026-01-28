# AI Copilot Instructions for Covoiturage (Carpooling) Application

## Project Overview
A Laravel 12 web application for local carpooling/ride-sharing. Currently in scaffolding phase with authentication system and core database structure in place. This project emphasizes rapid feature development within Laravel's proven patterns.

## Architecture & Key Components

### Tech Stack
- **Backend**: Laravel 12 with PHP 8.2+
- **Frontend**: Blade templates with Tailwind CSS v4 + Vite
- **Database**: SQLite (dev/testing) or MySQL/MariaDB (production)
- **Build**: Vite for asset bundling, Laravel Vite Plugin for HMR
- **Testing**: PHPUnit 11.5.3 with Mockery

### Database Foundation
**Core Tables** (see [database/migrations](database/migrations)):
- `users` - Base authentication system (Laravel default)
- `password_reset_tokens`, `sessions` - Auth infrastructure
- **Future entities needed**: trips/rides, bookings, locations, reviews (not yet created)

**Key Pattern**: Migrations use Laravel's Schema Builder with timestamps on all user-facing tables.

### Authentication
- Standard Laravel Authenticatable model in [app/Models/User.php](app/Models/User.php)
- `protected $fillable`: name, email, password
- Password auto-hashed via casts; email_verified_at nullable
- No custom guards or roles yet; plan for driver/passenger distinctions via policy

### Routing & Controllers
- Web routes only ([routes/web.php](routes/web.php)) - no API routes yet
- Single welcome view currently; extend with resource controllers for rides, bookings
- Use `Route::resource()` for standard CRUD operations
- Controllers inherit from [app/Http/Controllers/Controller.php](app/Http/Controllers/Controller.php)

## Developer Workflows

### Setup & Running
```bash
# Full environment setup (run once)
composer run-script setup    # Installs deps, copies .env, generates key, migrates, builds assets

# Development server (runs all services concurrently)
composer run-script dev      # Laravel server (8000) + queue (5173) + logs + Vite HMR
                             # Press Ctrl+C to stop all services at once

# Individual services for debugging
php artisan serve            # Laravel on http://localhost:8000
npm run dev                  # Vite on http://localhost:5173
php artisan tinker          # Interactive REPL for database debugging

# Testing
composer run-script test    # Clears config cache then runs PHPUnit (full suite)
php artisan test tests/Feature --parallel  # Run Feature tests only
```

### Database Operations
- Default connection is **SQLite** (configured in [config/database.php](config/database.php)); in-memory for tests
- Migrations stored in [database/migrations](database/migrations) with timestamps in filenames
- Create factories in [database/factories](database/factories); seeders in [database/seeders](database/seeders)
- Always use migrations for schema changes; never modify database directly
- Test database resets automatically per test run (see `phpunit.xml` test environment)

### Frontend Asset Pipeline
- Entry points: [resources/css/app.css](resources/css/app.css), [resources/js/app.js](resources/js/app.js)
- Vite configuration at [vite.config.js](vite.config.js) with Tailwind CSS plugin
- Blade views in [resources/views](resources/views) - use Laravel templating syntax
- HMR enabled during `npm run dev` via Laravel Vite Plugin

## Project-Specific Patterns

### Code Style
- PSR-4 autoloading: `App\` → [app/](app/), `Database\Factories\` → [database/factories/](database/factories/), etc.
- Eloquent ORM for all database queries (no raw SQL unless performance-critical)
- Type hints on all model properties and method returns
- Use Laravel facades (`Route::`, `Schema::`, etc.) for framework functionality

### Feature Implementation Path
When adding carpooling features (rides, bookings, reviews):
1. Create migration with relationships (e.g., Trip FK to user_id)
2. Add model in [app/Models/](app/Models/) with relationships defined
3. Generate factory in [database/factories/](database/factories/) for testing
4. Create resource controller in [app/Http/Controllers/](app/Http/Controllers/)
5. Add routes to [routes/web.php](routes/web.php) using `Route::resource()`
6. Create Blade views in [resources/views/](resources/views/) (groups by controller)
7. Add tests in [tests/Feature/](tests/Feature/) and [tests/Unit/](tests/Unit/)

### Configuration
- Environment variables in `.env` file (copy `.env.example` during setup)
- Key configs: [config/app.php](config/app.php), [config/auth.php](config/auth.php), [config/database.php](config/database.php)
- Mail, session, cache, queue configs available but uncustomized

### Testing
- Feature tests for HTTP flows in [tests/Feature/](tests/Feature/)
- Unit tests for business logic in [tests/Unit/](tests/Unit/)
- Use Laravel testing helpers: `$this->post()`, `$this->actingAs()`, database assertions
- Faker for test data generation ([fakerphp/faker](https://github.com/fzaninotto/Faker))

## Integration Points & Dependencies

### External Services (Configured but Not Implemented)
- **Mail** - Config in [config/mail.php](config/mail.php); add mailable classes as needed
- **Queue** - Config in [config/queue.php](config/queue.php); use `dispatch()` for async jobs
- **Cache** - Redis/array backends available; cache routes/queries for performance

### Package Notes
- **Laravel Tinker** - PHP REPL for debugging (run `php artisan tinker`)
- **Laravel Pail** - Log streaming tool
- **Laravel Pint** - Code formatter (PSR-12 compliant)
- **Collision** - Better error pages

## Important Gotchas & Conventions

1. **Mass Assignment**: Always define `$fillable` or `$guarded` on models to prevent security issues
2. **Relationships**: Use Eloquent relationships, not foreign keys directly in queries
3. **Migrations Naming**: Use descriptive names with timestamps; don't modify old migrations
4. **Blade Syntax**: `{{ }}` for escaping HTML, `{!! !!}` only for trusted content
5. **Service Providers**: Register bindings in [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php)
6. **No API Yet**: All routes are web routes; plan API namespace if REST API needed

## Code Examples for Common Tasks

### Creating a Model with Relationships
```php
// php artisan make:model Trip -m
namespace App\Models;
use Illuminate\Database\Eloquent\Model, Relations\BelongsTo;
class Trip extends Model {
    protected $fillable = ['origin', 'destination', 'date_time'];
    public function driver(): BelongsTo {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
```

### Writing a Feature Test
```php
// tests/Feature/TripTest.php
class TripTest extends TestCase {
    public function test_user_can_list_trips() {
        $response = $this->get('/trips');
        $response->assertStatus(200);
        $response->assertViewHas('trips');
    }
}
```

### Building a Resource Controller
```php
// php artisan make:controller TripController --resource
Route::resource('trips', TripController::class);
// Auto-generates: index, create, store, show, edit, update, destroy
```

## Quick Reference

| Task | Location | Command |
|------|----------|---------|
| Create model + migration | [app/Models/](app/Models/) | `php artisan make:model Trip -m` |
| Create controller | [app/Http/Controllers/](app/Http/Controllers/) | `php artisan make:controller TripController --resource` |
| Create view | [resources/views/](resources/views/) | Create `.blade.php` file |
| Run tests | [tests/](tests/) | `composer test` |
| Database interactions | REPL | `php artisan tinker` |
