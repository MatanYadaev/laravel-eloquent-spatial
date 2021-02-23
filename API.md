# API

## Available spatial classes

* `Point(float $latitude, float $longitude)` - [MySQL Point](https://dev.mysql.com/doc/refman/8.0/en/gis-class-point.html)
* `MultiPoint(Point[] | Collection<Point>)` - [MySQL MultiPoint](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multipoint.html)
* `LineString(Point[] | Collection<Point>)` - [MySQL LineString](https://dev.mysql.com/doc/refman/8.0/en/gis-class-linestring.html)
* `MultiLineString(LineString[] | Collection<LineString>)` - [MySQL MultiLineString](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multilinestring.html)
* `Polygon(LineString[] | Collection<LineString>)` - [MySQL Polygon](https://dev.mysql.com/doc/refman/8.0/en/gis-class-polygon.html)
* `MultiPolygon(Polygon[] | Collectiogit n<Polygon>)` - [MySQL MultiPolygon](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multipolygon.html)
* `GeometryCollection(Geometry[] | Collection<Geometry>)` - [MySQL GeometryCollection](https://dev.mysql.com/doc/refman/8.0/en/gis-class-geometrycollection.html)

## Available spatial functions

* `toWkt` - Mostly used internally
* `toArray`
* `toJson`
* `fromWkb` - Mostly used internally
* `fromJson`
* `toFeatureCollectionJson`
* `getCoordinates`

Geometry collection functions:

* `toCollectionWkt` - Mostly used internally
* `getGeometries` - (explanation...) Can be used with `ArrayAccess` as well

## Available spatial scopes

* [withDistance](#withDistance)
* [whereDistance](#whereDistance)
* [orderByDistance](#orderByDistance)
* [withDistanceSphere](#withDistanceSphere)
* [whereDistanceSphere](#whereDistanceSphere)
* [orderByDistanceSphere](#orderByDistanceSphere)

###  withDistance

Retrieves the distance between 2 geometry objects. Uses [ST_Distance](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-distance)

| parameter name      | type                 | default |
| ------------------  | -------------------- | ------- |
| `$column`           | `string`             |
| `$geometryOrColumn` | `Geometry \| string` |
| `$alias`            | `string`             | `'distance'`

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0)]);

$placeWithDistance = Place::query()
    ->withDistance('location', new Point(1, 1))
    ->first();

echo $placeWithDistance->distance; // 1.4142135623731

// when using alias:
$placeWithDistance = Place::query()
    ->withDistance('location', new Point(1, 1), 'distance_in_meters')
    ->first();

echo $placeWithDistance->distance_in_meters; // 1.4142135623731
```
</details>

###  whereDistance

Filters records by distance. Uses [ST_Distance](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-distance)

| parameter name      | type
| ------------------  | -------------------- 
| `$column`           | `string`
| `$geometryOrColumn` | `Geometry \| string` 
| `$operator`         | `string`
| `$value`            | `int \| float`

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0)]);
Place::create(['location' => new Point(100, 100)]);

$placesCountWithinDistance = Place::query()
    ->whereDistance('location', new Point(1, 1), '<', 1.5)
    ->count();

echo $placesCountWithinDistance; // 1
```
</details>

###  orderByDistance

Orders records by distance. Uses [ST_Distance](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-distance)

| parameter name      | type                 | default |
| ------------------  | -------------------- | ------- |
| `$column`           | `string`             |
| `$geometryOrColumn` | `Geometry \| string` |
| `$direction`         | `string`            | `'asc'`

<details><summary>Example</summary>

```php
Place::create([
    'name' => 'first',
    'location' => new Point(0, 0),
]);
Place::create([
    'name' => 'second',
    'location' => new Point(100, 100),
]);

$places = Place::query()
    ->orderByDistance('location', new Point(1, 1), 'desc')
    ->get();

echo $places[0]->name; // second
echo $places[1]->name; // first
```
</details>

###  withDistanceSphere

Retrieves the spherical distance between 2 geometry objects. Uses [ST_Distance_Sphere](https://dev.mysql.com/doc/refman/8.0/en/spatial-convenience-functions.html#function_st-distance-sphere)

| parameter name      | type                 | default |
| ------------------  | -------------------- | ------- |
| `$column`           | `string`             |
| `$geometryOrColumn` | `Geometry \| string` |
| `$alias`            | `string`             | `'distance'`

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0)]);

$placeWithDistance = Place::query()
    ->withDistanceSphere('location', new Point(1, 1))
    ->first();

echo $placeWithDistance->distance; // 157249.0357231545

// when using alias:
$placeWithDistance = Place::query()
    ->withDistanceSphere('location', new Point(1, 1), 'distance_in_meters')
    ->first();

echo $placeWithDistance->distance_in_meters; // 157249.0357231545
```
</details>

###  whereDistance

Filters records by spherical distance. Uses [ST_Distance_Sphere](https://dev.mysql.com/doc/refman/8.0/en/spatial-convenience-functions.html#function_st-distance-sphere)

| parameter name      | type
| ------------------  | -------------------- 
| `$column`           | `string`
| `$geometryOrColumn` | `Geometry \| string` 
| `$operator`         | `string`
| `$value`            | `int \| float`

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0)]);
Place::create(['location' => new Point(100, 100)]);

$placesCountWithinDistance = Place::query()
    ->whereDistance('location', new Point(1, 1), '<', 160000)
    ->count();

echo $placesCountWithinDistance; // 1
```
</details>

###  orderByDistance

Orders records by spherical distance. Uses [ST_Distance_Sphere](https://dev.mysql.com/doc/refman/8.0/en/spatial-convenience-functions.html#function_st-distance-sphere)

| parameter name      | type                 | default |
| ------------------  | -------------------- | ------- |
| `$column`           | `string`             |
| `$geometryOrColumn` | `Geometry \| string` |
| `$direction`         | `string`            | `'asc'`

<details><summary>Example</summary>

```php
Place::create([
    'name' => 'first',
    'location' => new Point(0, 0),
]);
Place::create([
    'name' => 'second',
    'location' => new Point(100, 100),
]);

$places = Place::query()
    ->orderByDistance('location', new Point(1, 1), 'desc')
    ->get();

echo $places[0]->name; // second
echo $places[1]->name; // first
```
</details>

* `whereWithin(string $column, Geometry | string $geometryOrColumn)`
* `whereContains(string $column, Geometry | string $geometryOrColumn)`
* `whereTouches(string $column, Geometry | string $geometryOrColumn)`
* `whereIntersects(string $column, Geometry | string $geometryOrColumn)`
* `whereCrosses(string $column, Geometry | string $geometryOrColumn)`
* `whereDisjoint(string $column, Geometry | string $geometryOrColumn)`
* `whereOverlaps(string $column, Geometry | string $geometryOrColumn)`
* `whereEquals(string $column, Geometry | string $geometryOrColumn)`
