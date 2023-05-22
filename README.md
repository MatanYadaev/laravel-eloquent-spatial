# Laravel Eloquent Spatial

[![Latest Version on Packagist](https://img.shields.io/packagist/v/matanyadaev/laravel-eloquent-spatial.svg?style=flat-square)](https://packagist.org/packages/matanyadaev/laravel-eloquent-spatial)
![Tests](https://github.com/matanyadaev/laravel-eloquent-spatial/workflows/Tests/badge.svg)
![Static code analysis](https://github.com/matanyadaev/laravel-eloquent-spatial/workflows/Static%20code%20analysis/badge.svg)
![Lint](https://github.com/matanyadaev/laravel-eloquent-spatial/workflows/Lint/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/matanyadaev/laravel-eloquent-spatial.svg?style=flat-square)](https://packagist.org/packages/matanyadaev/laravel-eloquent-spatial)

**This Laravel package allows you to easily work with spatial data types and functions.**

The latest version, v3, supports Laravel 10 and PHP 8.1+. For Laravel 8 or 9, and PHP 8.0, use v2.

This package supports MySQL v8, MySQL v5.7, and MariaDB v10.

## Getting Started

### Installing the Package

You can install the package via composer:

```bash
composer require matanyadaev/laravel-eloquent-spatial
```

### Setting Up Your First Model

1. First, generate a new model along with a migration file by running:

   ```bash
   php artisan make:model {modelName} --migration
   ```

2. Next, add some spatial columns to the migration file. For instance, to create a "places" table:

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

3. Run the migration:

    ```bash
    php artisan migrate
    ```

4. In your new model, fill the `$fillable` and `$casts` arrays and use the `HasSpatial` trait:

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

### Interacting with Spatial Data

After setting up your model, you can now create and access spatial data. Here's an example:

```php
use App\Models\Place;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Enums\Srid;

// Create new records

$londonEye = Place::create([
    'name' => 'London Eye',
    'location' => new Point(51.5032973, -0.1217424),
]);

$whiteHouse = Place::create([
    'name' => 'White House',
    'location' => new Point(38.8976763, -77.0365298, Srid::WGS84->value), // with SRID
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

// Access the data

echo $londonEye->location->latitude; // 51.5032973
echo $londonEye->location->longitude; // -0.1217424

echo $whiteHouse->location->srid; // 4326

echo $vacationCity->area->toJson(); // {"type":"Polygon","coordinates":[[[41.90746728266806,12.455363273620605],[41.906636872349075,12.450309991836548],[41.90197359839437,12.445632219314575],[41.90027269624499,12.447413206100464],[41.90000118654431,12.457906007766724],[41.90281205461268,12.458517551422117],[41.903107507989986,12.457584142684937],[41.905918239316286,12.457734346389769],[41.90637337450963,12.45572805404663],[41.90746728266806,12.455363273620605]]]}
```

## Further Reading

For more comprehensive documentation on the API, please refer to the [API](API.md) page.

## Tips for Improving IDE Support

In order to get better IDE support, you can add a `query` method phpDoc annotation to your model:

```php
/**
 * @method static SpatialBuilder query()
 */
class Place extends Model
{
    // ...
}
```

Or you could override the method:

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

You can add new methods to the `Geometry` class through macros.

Here's an example of how to register a macro in your service provider's `boot` method:

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

Use the method in your code:

```php
$londonEyePoint = new Point(51.5032973, -0.1217424);

echo $londonEyePoint->getName(); // Point
```

## Development

Here are some useful commands for development:

* Run tests: `composer pest`
* Run tests with coverage: `composer pest-coverage`
* Perform type checking: `composer phpstan`
* Format your code: `composer php-cs-fixer`

## Updates and Changes

For details on updates and changes, please refer to our [CHANGELOG](CHANGELOG.md).

## License

Laravel Eloquent Spatial is released under The MIT License (MIT). For more information, please see our [License File](LICENSE.md).
