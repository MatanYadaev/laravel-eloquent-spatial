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

###  withDistance

Retrieves the distance between 2 geometry objects.

| parameter name      | type                 | default |
| ------------------  | -------------------- | ------- |
| `$column`           | `string`             |
| `$geometryOrColumn` | `Geometry \| string` |
| `$alias`            | `string`             | `'distance'`

<details><summary>Example</summary>

```php
Place::create(['point' => new Point(0, 0)]);

$placeWithDistance = Place::query()
    ->withDistance('point', new Point(1, 1))
    ->first();

echo $placeWithDistance->distance; // 1.4142135623731

// when using alias:
$placeWithDistance = Place::query()
    ->withDistance('point', new Point(1, 1), 'distance_in_meters')
    ->first();

echo $placeWithDistance->distance_in_meters; // 1.4142135623731
```
</details>

###  whereDistance

Description

| parameter name      | type                 
| ------------------  | -------------------- 
| `$column`           | `string`             
| `$geometryOrColumn` | `Geometry \| string` 
| `$operator`         | `string`
| `$value`            | `int \| float`

<details><summary>Example</summary>

```php
Place::create([
    'name' => 'My place',
    'point' => new Point(0, 0),
]);

$place = Place::query()
    ->whereDistance('point', new Point(1, 1), '<', 10)
    ->first();

echo $place->name; // My place
```
</details>

* `whereDistance(string $column, Geometry | string $geometryOrColumn, string $operator, int | float $distance)`
* `orderByDistance(string $column, Geometry | string $geometryOrColumn, string $direction = 'asc')`
* `withDistanceSphere(string $column, Geometry | string $geometryOrColumn, string $alias = 'distance')`
* `whereDistanceSphere(string $column, Geometry | string $geometryOrColumn, string $operator, int | float $distance)`
* `orderByDistanceSphere(string $column, Geometry | string $geometryOrColumn, string $direction = 'asc')`
* `whereWithin(string $column, Geometry | string $geometryOrColumn)`
* `whereContains(string $column, Geometry | string $geometryOrColumn)`
* `whereTouches(string $column, Geometry | string $geometryOrColumn)`
* `whereIntersects(string $column, Geometry | string $geometryOrColumn)`
* `whereCrosses(string $column, Geometry | string $geometryOrColumn)`
* `whereDisjoint(string $column, Geometry | string $geometryOrColumn)`
* `whereOverlaps(string $column, Geometry | string $geometryOrColumn)`
* `whereEquals(string $column, Geometry | string $geometryOrColumn)`
