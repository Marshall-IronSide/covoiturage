# AI Copilot Instructions for Covoiturage (Carpooling) Application

## Project Overview
A Laravel 12 carpooling web application with full authentication, trip management, vehicle registration, and reservation system. Core features implemented; expanding with reviews and advanced booking logic.

## Architecture & Key Components

### Tech Stack
- **Backend**: Laravel 12 with PHP 8.2+
- **Frontend**: Blade templates with Tailwind CSS v4 + Vite
- **Database**: SQLite (dev/testing) or MySQL/MariaDB (production)
- **Build**: Vite for asset bundling, Laravel Vite Plugin for HMR
- **Testing**: PHPUnit 11.5.3 with Mockery

### Core Domain Model
**Data Structure** - Users have dual roles (driver/passenger):
1. **User** ([app/Models/User.php](app/Models/User.php)): `prenom`, `nom`, `email`, `password`, `telephone`
   - Owns ONE `Vehicule` (driver registration required before trip creation)
   - Creates many `Trajet` (trips) as `conducteur_id` (driver)
   - Makes many `Reservation` (bookings) as `passager_id` (passenger)
2. **Trajet** ([app/Models/Trajet.php](app/Models/Trajet.php)): Fields `ville_depart`, `description_depart`, `ville_arrivee`, `description_arrivee`, `date_trajet`, `places_disponibles`, `conducteur_id`, `vehicule_id`
   - Requires user to have vehicle; auto-assigns vehicle in `TrajetController::store()`
   - Has many `Reservation` bookings
3. **Reservation** ([app/Models/Reservation.php](app/Models/Reservation.php)): `trajet_id`, `passager_id`, `nombre_places`, `prix_total`, `statut`
   - Links passengers to trips; supports multiple seats per booking
   - Can have many `Avis` (reviews)
4. **Vehicule** ([app/Models/Vehicule.php](app/Models/Vehicule.php)): `user_id`, `numero_plaque`, `photo`, `description`
   - One-to-one with User; used across many `Trajet`

### Authorization Pattern
- **Policies** in [app/Policies/](app/Policies/): `TrajetPolicy`, `ReservationPolicy`
- `TrajetPolicy::update()` & `delete()`: Only trip creator (`conducteur_id`) can modify/delete
- Authorization check: `$this->authorize('update', $trajet)` in controllers
- **Future**: Implement passenger/driver distinctions for view access

## Developer Workflows

### Setup & Running
```bash
# Full environment setup (run once)
composer run-script setup    # Installs deps, copies .env, generates key, migrates, builds assets

# Development server (runs all services concurrently - press Ctrl+C to stop all)
composer run-script dev      # Laravel (8000) + queue listener + Pail logs + Vite HMR

# Individual services for debugging
php artisan serve            # Laravel on http://localhost:8000
npm run dev                  # Vite HMR server on http://localhost:5173
php artisan tinker          # Interactive REPL for model testing and database queries

# Testing
composer run-script test    # Clears config cache, runs PHPUnit
php artisan test tests/Feature --parallel  # Run Feature tests only (faster)
```

### Key Routes & Controllers
**Routes** ([routes/web.php](routes/web.php)) - Manual mixed routing (not all `Route::resource()`):
- **Trajets**: `GET /trajets` (list), `GET /trajets/create`, `POST /trajets`, `GET /trajets/{trajet}`, `GET /trajets/{trajet}/edit`, `PATCH /trajets/{trajet}`, `DELETE /trajets/{trajet}`
  - Note: Route `create` explicitly defined BEFORE `{trajet}` route to prevent collision
  - Controller: [TrajetController](app/Http/Controllers/TrajetController.php) - enforces vehicle requirement in `create()` & `store()`
- **Vehicules**: `GET /vehicule/create`, `POST /vehicule`, `GET /vehicule/{vehicule}`, `GET /vehicule/{vehicule}/edit`, `PATCH /vehicule/{vehicule}`, `DELETE /vehicule/{vehicule}`
  - Controller: [VehiculeController](app/Http/Controllers/VehiculeController.php)
- **Reservations**: `GET /reservations`, `POST /reservations`, etc.
  - Controller: [ReservationController](app/Http/Controllers/ReservationController.php)
- **Auth**: Provided by Breeze (handled in [routes/auth.php](routes/auth.php))
- **Dashboard**: `GET /dashboard` → [DashboardController](app/Http/Controllers/DashboardController.php)

**Middleware**: Auth-required routes use `Route::middleware('auth')->group()`

### Database Operations
- Default: **SQLite** ([database.php](config/database.php) `:memory:` for tests)
- Migrations: [database/migrations](database/migrations) with timestamp prefixes - CURRENT ISSUE: Trip migration has unused `photo_vehicule` & `description_vehicule` fields (vehicles are separate entity now)
- Always create factories in [database/factories](database/factories/) for model seeding
- Seeders in [database/seeders](database/seeders)
- Use Eloquent relationships, never bypass with raw FK queries

### Frontend Structure
- Entry: [resources/css/app.css](resources/css/app.css) + [resources/js/app.js](resources/js/app.js)
- Views: [resources/views/](resources/views/) organized by feature (trajets/, reservations/, vehicules/, auth/, profile/)
- Blade syntax: `{{ }}` (escaped), `{!! !!}` (trusted), `@if`, `@foreach`, `@auth`, `@guest`
- Tailwind v4 + Vite HMR enabled during `npm run dev`
- Reusable components in [resources/views/components/](resources/views/components/)

## Project-Specific Patterns

### Code Style
- **PSR-4 autoloading**: `App\` → [app/](app/), `Database\Factories\` → [database/factories/](database/factories/), `Tests\` → [tests/](tests/)
- **Eloquent ORM only**: No raw SQL; use relationships defined in models
- **Type hints required**: All method parameters and returns typed (`string`, `bool`, `BelongsTo`, etc.)
- **Model relationships**: Define in model class; eager-load in controllers with `->with(['relation1', 'relation2'])`
- **Mass assignment safety**: All models must have `protected $fillable` (see [Vehicule.php](app/Models/Vehicule.php#L16) as example)
- **DateTime handling**: Use `protected $casts = ['date_trajet' => 'datetime']` for automatic Carbon conversion
- **Custom attributes**: Use `#[Attribute]` or magic `getXxxAttribute()` for computed properties (see `getNomCompletAttribute()` in User)

### Authorization & Security
- **Policies first**: Check `$this->authorize('action', $model)` in controllers BEFORE any data manipulation
- **Policy methods**: Match controller actions (`update` policy → `update()` method; `delete` → `delete()`)
- **Example**: `TrajetPolicy::update()` ensures only trip creator (`conducteur_id`) can edit - prevents passenger unauthorized updates
- **Middleware**: Use `Route::middleware('auth')` to protect routes; no separate guard needed yet

### Feature Implementation Checklist
When adding new features (e.g., reviews, messaging, disputes):
1. **Migration** ([database/migrations](database/migrations/)): Define schema with FKs and indexes
2. **Model** ([app/Models/](app/Models/)): Define relationships, `$fillable`, `$casts`
3. **Factory** ([database/factories/](database/factories/)): For testing (use [UserFactory.php](database/factories/UserFactory.php) as template)
4. **Controller** ([app/Http/Controllers/](app/Http/Controllers/)): Resource methods with auth checks
5. **Policy** ([app/Policies/](app/Policies/)): Define who can view/create/update/delete (copy [TrajetPolicy.php](app/Policies/TrajetPolicy.php))
6. **Routes** ([routes/web.php](routes/web.php)): Add routes with proper middleware grouping
7. **Views** ([resources/views/](resources/views/)): Create `.blade.php` files in feature subdirectory
8. **Tests** ([tests/Feature/](tests/Feature/), [tests/Unit/](tests/Unit/)): Write test class extending `TestCase`

### Validation & Error Handling
- **Request validation**: Use `$request->validate([...])` in controllers; Laravel returns 422 with errors automatically
- **Custom messages**: Pass third argument to `validate()`: `validate([...], [], ['field.rule' => 'Custom message'])`
- **Relationship validation**: When storing, validate FKs exist: `'trajet_id' => 'required|exists:trajets,id'`
- **Auth guard**: Use `auth()->id()` to get current user ID; `auth()->user()` for full User object

### Testing Pattern
- **Test class location**: [tests/Feature/SomeTest.php](tests/Feature/) for HTTP tests
- **Setup**: Extend `Tests\TestCase` which provides Laravel test helpers
- **Helpers**: `$this->actingAs($user)`, `$this->get('route')`, `$this->post('route', ['data'])`, `$this->assertDatabaseHas('table', ['col' => 'val'])`
- **Example**: [tests/Feature/ExampleTest.php](tests/Feature/ExampleTest.php) - use as template
- **Run**: `php artisan test` or `composer test`

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
6. **Route Name Ordering**: Define explicit routes (e.g., `/trajets/create`) BEFORE parameterized routes (`/trajets/{trajet}`) to prevent shadowing
7. **Vehicle Requirement**: Driver MUST register vehicle before creating trips - check in `TrajetController::create()` and `store()`
8. **Unused Migration Fields**: Trip migration still has `photo_vehicule` & `description_vehicule` (should be cleaned up; vehicles are now a separate entity)

## Code Examples for Common Tasks

### Creating a Model with Relationships
```php
// php artisan make:model Avis -m -f
namespace App\Models;
use Illuminate\Database\Eloquent\Model, Relations\BelongsTo;
class Avis extends Model {
    protected $fillable = ['reservation_id', 'note', 'commentaire'];
    protected $casts = ['note' => 'integer', 'created_at' => 'datetime'];
    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }
}
```

### Writing a Feature Test
```php
// tests/Feature/TrajetTest.php
public function test_driver_can_create_trip_after_registering_vehicle() {
    $user = User::factory()->create();
    $vehicle = Vehicule::factory()->for($user)->create();
    $this->actingAs($user)->post('/trajets', [
        'ville_depart' => 'Paris',
        'ville_arrivee' => 'Lyon',
        'date_trajet' => now()->addDays(5),
        'places_disponibles' => 4,
        'description_depart' => 'Gare du Nord',
        'description_arrivee' => 'Gare Part-Dieu'
    ])->assertRedirect(route('trajets.index'));
    $this->assertDatabaseHas('trajets', ['conducteur_id' => $user->id]);
}
```

### Authorization Check in Controller
```php
// In TrajetController::update()
public function update(Request $request, Trajet $trajet) {
    $this->authorize('update', $trajet); // Calls TrajetPolicy::update()
    $validated = $request->validate([...]);
    $trajet->update($validated);
    return redirect()->route('trajets.show', $trajet);
}
```

## Quick Reference

| Task | Location | Command |
|------|----------|---------|
| Create model + migration | [app/Models/](app/Models/) | `php artisan make:model Trip -m` |
| Create controller | [app/Http/Controllers/](app/Http/Controllers/) | `php artisan make:controller TripController --resource` |
| Create policy | [app/Policies/](app/Policies/) | `php artisan make:policy TripPolicy --model=Trip` |
| Create factory | [database/factories/](database/factories/) | `php artisan make:factory TripFactory --model=Trip` |
| Create view | [resources/views/](resources/views/) | Create `.blade.php` file in feature directory |
| Run tests | [tests/](tests/) | `composer test` |
| Database interactions | REPL | `php artisan tinker` |
| Format code | Whole project | `php artisan pint` |
