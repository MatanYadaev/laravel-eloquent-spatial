# Laravel Eloquent Spatial

[![Latest Version on Packagist](https://img.shields.io/packagist/v/matanyadaev/laravel-eloquent-spatial.svg?style=flat-square)](https://packagist.org/packages/matanyadaev/laravel-eloquent-spatial)
![Tests](https://img.shields.io/github/workflow/status/matanyadaev/laravel-eloquent-spatial/Tests?label=tests)
![Static code analysis](https://github.com/matanyadaev/laravel-eloquent-spatial/workflows/Static%20code%20analysis/badge.svg)
![Lint](https://github.com/matanyadaev/laravel-eloquent-spatial/workflows/Lint/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/matanyadaev/laravel-eloquent-spatial.svg?style=flat-square)](https://packagist.org/packages/matanyadaev/laravel-eloquent-spatial)

Laravel package to work with spatial data types and functions.

This package supports MySQL v8, MySQL v5.7, and MariaDB v10.

## Installation

You can install the package via composer:

```bash
composer require matanyadaev/laravel-eloquent-spatial
```

## Quickstart
Generate a new model with a migration file:
```bash
php artisan make:model {modelName} --migration
```

Add some spatial columns to the migration file:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlacesTable extends Migration
{
    public function up(): void
    {
        Schema::create('places', static function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->point('location')->nullable();
            $table->polygon('area')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('places');
    }
}
```

Run the migration:

```bash
php artisan migrate
```

Fill the `$fillable` and `$casts` arrays and use the `HasSpatial` trait in your new model:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

/**
 * @property Point $location
 * @property Polygon $area
 * @method static SpatialBuilder query()
 */
class Place extends Model
{
    use HasSpatial;

    protected $fillable = [
        'name',
        'location',
        'area',
    ];

    protected $casts = [
        'location' => Point::class,
        'area' => Polygon::class,
    ];
}
```

Access spatial data:

```php
use App\Models\Place;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;

$londonEye = Place::create([
    'name' => 'London Eye',
    'location' => new Point(51.5032973, -0.1217424),
]);

$whiteHouse = Place::create([
    'name' => 'White House',
    'location' => new Point(38.8976763, -77.0365298, 4326), // with SRID
]);

$vaticanCity = Place::create([
    'name' => 'Vatican City',
    'area' => new Polygon([
        new LineString([
              new Point(12.455363273620605, 41.90746728266806),
              new Point(12.450309991836548, 41.906636872349075),
              new Point(12.445632219314575, 41.90197359839437),
              new Point(12.447413206100464, 41.90027269624499),
              new Point(12.457906007766724, 41.90000118654431),
              new Point(12.458517551422117, 41.90281205461268),
              new Point(12.457584142684937, 41.903107507989986),
              new Point(12.457734346389769, 41.905918239316286),
              new Point(12.45572805404663, 41.90637337450963),
              new Point(12.455363273620605, 41.90746728266806),
        ]),
    ]),
])
```

Retrieve a record with spatial data:

```php
echo $londonEye->location->latitude; // 51.5032973
echo $londonEye->location->longitude; // -0.1217424

echo $whiteHouse->location->srid; // 4326

echo $vacationCity->area->toJson(); // {"type":"Polygon","coordinates":[[[41.90746728266806,12.455363273620605],[41.906636872349075,12.450309991836548],[41.90197359839437,12.445632219314575],[41.90027269624499,12.447413206100464],[41.90000118654431,12.457906007766724],[41.90281205461268,12.458517551422117],[41.903107507989986,12.457584142684937],[41.905918239316286,12.457734346389769],[41.90637337450963,12.45572805404663],[41.90746728266806,12.455363273620605]]]}
```

## API

Please see [API](API.md) for more informative API documentation.

## Tip for better IDE support

In order to get better IDE support, you should add a `query` method phpDoc annotation to your model:

```php
/**
 * @method static SpatialBuilder query()
 */
class Place extends Model
{
    // ...
}
```

Or alternatively override the method:

```php
class Place extends Model
{
    public static function query(): SpatialBuilder
    {
        return parent::query();
    }
}
```

Create queries only with the `query()` static method:

```php
Place::query()->whereDistance(...); // This is IDE-friendly
Place::whereDistance(...); // This is not
```

## Extension

You can extend the package by adding macro methods to the `Geometry` class.

Register a macro in the `boot` method of your service provider:

```php
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Geometry::macro('getName', function (): string {
            /** @var Geometry $this */
            return class_basename($this);
        });
    }
}
```

Use the macro in your code:

```php
$londonEyePoint = new Point(51.5032973, -0.1217424);

echo $londonEyePoint->getName(); // Point
```

## Development

* Test: `composer pest`
* Test with coverage: `composer pest-coverage`
* Type check: `composer phpstan`
* Format: `composer php-cs-fixer`

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
