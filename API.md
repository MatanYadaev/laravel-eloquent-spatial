# API

## Available geometry classes

* `Point(float $latitude, float $longitude, int $srid = 0)` - [MySQL Point](https://dev.mysql.com/doc/refman/8.0/en/gis-class-point.html)
* `MultiPoint(Point[] | Collection<Point> $geometries, int $srid = 0)` - [MySQL MultiPoint](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multipoint.html)
* `LineString(Point[] | Collection<Point> $geometries, int $srid = 0)` - [MySQL LineString](https://dev.mysql.com/doc/refman/8.0/en/gis-class-linestring.html)
* `MultiLineString(LineString[] | Collection<LineString> $geometries, int $srid = 0)` - [MySQL MultiLineString](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multilinestring.html)
* `Polygon(LineString[] | Collection<LineString> $geometries, int $srid = 0)` - [MySQL Polygon](https://dev.mysql.com/doc/refman/8.0/en/gis-class-polygon.html)
* `MultiPolygon(Polygon[] | Collection<Polygon> $geometries, int $srid = 0)` - [MySQL MultiPolygon](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multipolygon.html)
* `GeometryCollection(Geometry[] | Collection<Geometry> $geometries, int $srid = 0)` - [MySQL GeometryCollection](https://dev.mysql.com/doc/refman/8.0/en/gis-class-geometrycollection.html)

Geometry classes can be also created by these static methods:

* `fromArray(array $geometry)` - Creates a geometry object from a [GeoJSON](https://en.wikipedia.org/wiki/GeoJSON) array.
* `fromJson(string $geoJson, int $srid = 0)` - Creates a geometry object from a [GeoJSON](https://en.wikipedia.org/wiki/GeoJSON) string.
* `fromWkt(string $wkt, int $srid = 0)` - Creates a geometry object from a [WKT](https://en.wikipedia.org/wiki/Well-known_text_representation_of_geometry).
* `fromWkb(string $wkb, int $srid = 0)` - Creates a geometry object from a [WKB](https://en.wikipedia.org/wiki/Well-known_text_representation_of_geometry#Well-known_binary).

## Available geometry class methods

* `toArray()` - Serializes the geometry object into a GeoJSON associative array.
* `toJson()` - Serializes the geometry object into an GeoJSON string.
* `toFeatureCollectionJson()` - Serializes the geometry object into an GeoJSON's FeatureCollection string.
* `toWkt()` - Serializes the geometry object into a WKT.
* `toWkb()` - Serializes the geometry object into a WKB.
* `getCoordinates()` - Returns the coordinates of the geometry object.
* `toSqlExpression(ConnectionInterface $connection)` - Serializes the geometry object into an SQL query.
In addition, `GeometryCollection` also has these functions:

* `getGeometries()` - Returns a geometry array. Can be used with `ArrayAccess` as well.

```php
$geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]),
]);

echo $geometryCollection->getGeometries()[1]->latitude; // 0
// or access as an array:
echo $geometryCollection[1]->latitude; // 0
```

## Available spatial scopes

* [withDistance](#withDistance)
* [whereDistance](#whereDistance)
* [orderByDistance](#orderByDistance)
* [withDistanceSphere](#withDistanceSphere)
* [whereDistanceSphere](#whereDistanceSphere)
* [orderByDistanceSphere](#orderByDistanceSphere)
* [whereWithin](#whereWithin)
* [whereNotWithin](#whereNotWithin)
* [whereContains](#whereContains)
* [whereNotContains](#whereNotContains)
* [whereTouches](#whereTouches)
* [whereIntersects](#whereIntersects)
* [whereCrosses](#whereCrosses)
* [whereDisjoint](#whereDisjoint)
* [whereEquals](#whereEquals)
* [whereSrid](#whereSrid)

###  withDistance

Retrieves the distance between 2 geometry objects. Uses [ST_Distance](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-distance).

| parameter name      | type                | default      |
|---------------------|---------------------|--------------|
| `$column`           | `string`            |              |
| `$geometryOrColumn` | `Geometry \ string` |              |
| `$alias`            | `string`            | `'distance'` |

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0, 4326)]);

$placeWithDistance = Place::query()
    ->withDistance('location', new Point(1, 1, 4326))
    ->first();

echo $placeWithDistance->distance; // 156897.79947260793

// when using alias:
$placeWithDistance = Place::query()
    ->withDistance('location', new Point(1, 1, 4326), 'distance_in_meters')
    ->first();

echo $placeWithDistance->distance_in_meters; // 156897.79947260793
```
</details>

###  whereDistance

Filters records by distance. Uses [ST_Distance](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-distance).

| parameter name      | type                |
|---------------------|---------------------|
| `$column`           | `string`            |
| `$geometryOrColumn` | `Geometry \ string` |
| `$operator`         | `string`            |
| `$value`            | `int \ float`       |

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0, 4326)]);
Place::create(['location' => new Point(50, 50, 4326)]);

$placesCountWithinDistance = Place::query()
    ->whereDistance('location', new Point(1, 1, 4326), '<', 160000)
    ->count();

echo $placesCountWithinDistance; // 1
```
</details>

###  orderByDistance

Orders records by distance. Uses [ST_Distance](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-distance).

| parameter name      | type                | default |
|---------------------|---------------------|---------|
| `$column`           | `string`            |         |
| `$geometryOrColumn` | `Geometry \ string` |         |
| `$direction`        | `string`            | `'asc'` |

<details><summary>Example</summary>

```php
Place::create([
    'name' => 'first',
    'location' => new Point(0, 0, 4326),
]);
Place::create([
    'name' => 'second',
    'location' => new Point(50, 50, 4326),
]);

$places = Place::query()
    ->orderByDistance('location', new Point(1, 1, 4326), 'desc')
    ->get();

echo $places[0]->name; // second
echo $places[1]->name; // first
```
</details>

###  withDistanceSphere

Retrieves the spherical distance between 2 geometry objects. Uses [ST_Distance_Sphere](https://dev.mysql.com/doc/refman/8.0/en/spatial-convenience-functions.html#function_st-distance-sphere).

| parameter name      | type                | default      |
|---------------------|---------------------|--------------|
| `$column`           | `string`            |              |
| `$geometryOrColumn` | `Geometry \ string` |              |
| `$alias`            | `string`            | `'distance'` |

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0, 4326)]);

$placeWithDistance = Place::query()
    ->withDistanceSphere('location', new Point(1, 1, 4326))
    ->first();

echo $placeWithDistance->distance; // 157249.59776850493

// when using alias:
$placeWithDistance = Place::query()
    ->withDistanceSphere('location', new Point(1, 1, 4326), 'distance_in_meters')
    ->first();

echo $placeWithDistance->distance_in_meters; // 157249.59776850493
```
</details>

###  whereDistanceSphere

Filters records by spherical distance. Uses [ST_Distance_Sphere](https://dev.mysql.com/doc/refman/8.0/en/spatial-convenience-functions.html#function_st-distance-sphere).

| parameter name      | type                |
|---------------------|---------------------|
| `$column`           | `string`            |
| `$geometryOrColumn` | `Geometry \ string` |
| `$operator`         | `string`            |
| `$value`            | `int \ float`       |

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0, 4326)]);
Place::create(['location' => new Point(50, 50, 4326)]);

$placesCountWithinDistance = Place::query()
    ->whereDistanceSphere('location', new Point(1, 1, 4326), '<', 160000)
    ->count();

echo $placesCountWithinDistance; // 1
```
</details>

###  orderByDistanceSphere

Orders records by spherical distance. Uses [ST_Distance_Sphere](https://dev.mysql.com/doc/refman/8.0/en/spatial-convenience-functions.html#function_st-distance-sphere).

| parameter name      | type                | default |
|---------------------|---------------------|---------|
| `$column`           | `string`            |         |
| `$geometryOrColumn` | `Geometry \ string` |         |
| `$direction`        | `string`            | `'asc'` |

<details><summary>Example</summary>

```php
Place::create([
    'name' => 'first',
    'location' => new Point(0, 0, 4326),
]);
Place::create([
    'name' => 'second',
    'location' => new Point(100, 100, 4326),
]);

$places = Place::query()
    ->orderByDistanceSphere('location', new Point(1, 1, 4326), 'desc')
    ->get();

echo $places[0]->name; // second
echo $places[1]->name; // first
```
</details>

###  whereWithin

Filters records by the [ST_Within](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-within) function.

| parameter name      | type                |
|---------------------|---------------------|
| `$column`           | `string`            |
| `$geometryOrColumn` | `Geometry \ string` |

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0, 4326)]);

Place::query()
    ->whereWithin('location', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'))
    ->exists(); // true
```
</details>

###  whereNotWithin

Filters records by the [ST_Within](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-within) function.

| parameter name      | type                |
|---------------------|---------------------|
| `$column`           | `string`            |
| `$geometryOrColumn` | `Geometry \ string` |

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0, 4326)]);

Place::query()
    ->whereNotWithin('location', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'))
    ->exists(); // false
```
</details>

###  whereContains

Filters records by the [ST_Contains](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-contains) function.

| parameter name      | type                |
|---------------------|---------------------|
| `$column`           | `string`            |
| `$geometryOrColumn` | `Geometry \ string` |

<details><summary>Example</summary>

```php
Place::create(['area' => Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'),]);

Place::query()
    ->whereContains('area', new Point(0, 0, 4326))
    ->exists(); // true
```
</details>

###  whereNotContains

Filters records by the [ST_Contains](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-contains) function.

| parameter name      | type                |
|---------------------|---------------------|
| `$column`           | `string`            |
| `$geometryOrColumn` | `Geometry \ string` |

<details><summary>Example</summary>

```php
Place::create(['area' => Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'),]);

Place::query()
    ->whereNotContains('area', new Point(0, 0, 4326))
    ->exists(); // false
```
</details>

###  whereTouches

Filters records by the [ST_Touches](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-touches) function.

| parameter name      | type                |
|---------------------|---------------------|
| `$column`           | `string`            |
| `$geometryOrColumn` | `Geometry \ string` |

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0, 4326)]);

Place::query()
    ->whereTouches('location', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[0,-1],[0,0],[-1,0],[-1,-1]]]}'))
    ->exists(); // true
```
</details>

###  whereIntersects

Filters records by the [ST_Intersects](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-intersects) function.

| parameter name      | type                |
|---------------------|---------------------|
| `$column`           | `string`            |
| `$geometryOrColumn` | `Geometry \ string` |

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0, 4326)]);

Place::query()
    ->whereIntersects('location', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'))
    ->exists(); // true
```
</details>

###  whereCrosses

Filters records by the [ST_Crosses](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-crosses) function.

| parameter name      | type                |
|---------------------|---------------------|
| `$column`           | `string`            |
| `$geometryOrColumn` | `Geometry \ string` |

<details><summary>Example</summary>

```php
Place::create(['line_string' => LineString::fromJson('{"type":"LineString","coordinates":[[0,0],[2,0]]}')]);

Place::query()
    ->whereCrosses('line_string', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'))
    ->exists(); // true
```
</details>

###  whereDisjoint

Filters records by the [ST_Disjoint](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-disjoint) function.

| parameter name      | type                |
|---------------------|---------------------|
| `$column`           | `string`            |
| `$geometryOrColumn` | `Geometry \ string` |

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0, 4326)]);

Place::query()
    ->whereDisjoint('location', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[-0.5,-1],[-0.5,-0.5],[-1,-0.5],[-1,-1]]]}'))
    ->exists(); // true
```
</details>

###  whereEquals

Filters records by the [ST_Equal](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-equals) function.

| parameter name      | type                |
|---------------------|---------------------|
| `$column`           | `string`            |
| `$geometryOrColumn` | `Geometry \ string` |

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0, 4326)]);

Place::query()
    ->whereEquals('location', new Point(0, 0, 4326))
    ->exists(); // true
```
</details>

###  whereSrid

Filters records by the [ST_Srid](https://dev.mysql.com/doc/refman/8.0/en/gis-general-property-functions.html#function_st-srid) function.

| parameter name | type     |
|----------------|----------|
| `$column`      | `string` |
| `$operator`    | `string` |
| `$value`       | `int`    |

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0, 4326)]);

Place::query()
    ->whereSrid('location', '=', 4326)
    ->exists(); // true
```
</details>

