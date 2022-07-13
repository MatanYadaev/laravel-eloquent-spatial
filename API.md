# API

## Available spatial classes

* `Point(float $latitude, float $longitude)` - [MySQL Point](https://dev.mysql.com/doc/refman/8.0/en/gis-class-point.html)
* `MultiPoint(Point[] | Collection<Point>)` - [MySQL MultiPoint](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multipoint.html)
* `LineString(Point[] | Collection<Point>)` - [MySQL LineString](https://dev.mysql.com/doc/refman/8.0/en/gis-class-linestring.html)
* `MultiLineString(LineString[] | Collection<LineString>)` - [MySQL MultiLineString](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multilinestring.html)
* `Polygon(LineString[] | Collection<LineString>)` - [MySQL Polygon](https://dev.mysql.com/doc/refman/8.0/en/gis-class-polygon.html)
* `MultiPolygon(Polygon[] | Collection<Polygon>)` - [MySQL MultiPolygon](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multipolygon.html)
* `GeometryCollection(Geometry[] | Collection<Geometry>)` - [MySQL GeometryCollection](https://dev.mysql.com/doc/refman/8.0/en/gis-class-geometrycollection.html)

## Available spatial functions

Every geometry class has these functions:

* `toArray()` - Serializes the geometry object into a GeoJSON associative array.
* `toJson()` - Serializes the geometry object into an GeoJSON string.
* `fromJson(string $geoJson)` - Deserializes a geometry object from a GeoJSON string. (static method) 
* `toFeatureCollectionJson()` - Serializes the geometry object into an GeoJSON's FeatureCollection string.
* `getCoordinates()` - Returns the coordinates of the geometry object.
* `getSrid()` - Returns the srid of the geometry object.

In addition, `GeometryCollection` also has these functions:

* `getGeometries()` - Returns a geometry array. Can be used with `ArrayAccess` as well.

```php
$geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(180, 0),
                new Point(179, 1),
                new Point(178, 2),
                new Point(177, 3),
                new Point(180, 0),
            ]),
        ]),
        new Point(180, 0),
    ]),
]);

echo $geometryCollection->getGeometries()[1]->latitude; // 180
// can also access as an array:
echo $geometryCollection[1]->latitude; // 180
```

## Available spatial scopes

* [withDistance](#withDistance)
* [whereDistance](#whereDistance)
* [orderByDistance](#orderByDistance)
* [withDistanceSphere](#withDistanceSphere)
* [whereDistanceSphere](#whereDistanceSphere)
* [orderByDistanceSphere](#orderByDistanceSphere)
* [whereWithin](#whereWithin)
* [whereContains](#whereContains)
* [whereTouches](#whereTouches)
* [whereIntersects](#whereIntersects)
* [whereCrosses](#whereCrosses)
* [whereDisjoint](#whereDisjoint)
* [whereEquals](#whereEquals)
* [whereSrid](#whereSrid)

###  withDistance

Retrieves the distance between 2 geometry objects. Uses [ST_Distance](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-distance).

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

Filters records by distance. Uses [ST_Distance](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-distance).

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

Orders records by distance. Uses [ST_Distance](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-distance).

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

Retrieves the spherical distance between 2 geometry objects. Uses [ST_Distance_Sphere](https://dev.mysql.com/doc/refman/8.0/en/spatial-convenience-functions.html#function_st-distance-sphere).

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

###  whereDistanceSphere

Filters records by spherical distance. Uses [ST_Distance_Sphere](https://dev.mysql.com/doc/refman/8.0/en/spatial-convenience-functions.html#function_st-distance-sphere).

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
    ->whereDistanceSphere('location', new Point(1, 1), '<', 160000)
    ->count();

echo $placesCountWithinDistance; // 1
```
</details>

###  orderByDistanceSphere

Orders records by spherical distance. Uses [ST_Distance_Sphere](https://dev.mysql.com/doc/refman/8.0/en/spatial-convenience-functions.html#function_st-distance-sphere).

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
    ->orderByDistanceSphere('location', new Point(1, 1), 'desc')
    ->get();

echo $places[0]->name; // second
echo $places[1]->name; // first
```
</details>

###  whereWithin

Filters records by the [ST_Within](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-within) function.

| parameter name      | type                 
| ------------------  | -------------------- 
| `$column`           | `string`             
| `$geometryOrColumn` | `Geometry \| string` 

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0)]);

Place::query()
    ->whereWithin('location', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'))
    ->exists(); // true
```
</details>

###  whereContains

Filters records by the [ST_Contains](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-contains) function.

| parameter name      | type                 
| ------------------  | -------------------- 
| `$column`           | `string`             
| `$geometryOrColumn` | `Geometry \| string` 

<details><summary>Example</summary>

```php
Place::create(['area' => Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'),]);

Place::query()
    ->whereContains('area', new Point(0, 0))
    ->exists(); // true
```
</details>

###  whereTouches

Filters records by the [ST_Touches](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-touches) function.

| parameter name      | type                 
| ------------------  | -------------------- 
| `$column`           | `string`             
| `$geometryOrColumn` | `Geometry \| string` 

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0)]);

Place::query()
    ->whereTouches('location', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[0,-1],[0,0],[-1,0],[-1,-1]]]}'))
    ->exists(); // true
```
</details>

###  whereIntersects

Filters records by the [ST_Intersects](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-intersects) function.

| parameter name      | type                 
| ------------------  | -------------------- 
| `$column`           | `string`             
| `$geometryOrColumn` | `Geometry \| string` 

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0)]);

Place::query()
    ->whereIntersects('location', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'))
    ->exists(); // true
```
</details>

###  whereCrosses

Filters records by the [ST_Crosses](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-crosses) function.

| parameter name      | type                 
| ------------------  | -------------------- 
| `$column`           | `string`             
| `$geometryOrColumn` | `Geometry \| string` 

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

| parameter name      | type
| ------------------  | -------------------- 
| `$column`           | `string`
| `$geometryOrColumn` | `Geometry \| string` 

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0)]);

Place::query()
    ->whereDisjoint('location', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[-0.5,-1],[-0.5,-0.5],[-1,-0.5],[-1,-1]]]}'))
    ->exists(); // true
```
</details>

###  whereEquals

Filters records by the [ST_Equal](https://dev.mysql.com/doc/refman/8.0/en/spatial-relation-functions-object-shapes.html#function_st-equals) function.

| parameter name      | type
| ------------------  | -------------------- 
| `$column`           | `string`
| `$geometryOrColumn` | `Geometry \| string` 

<details><summary>Example</summary>

```php
Place::create(['location' => new Point(0, 0)]);

Place::query()
    ->whereEquals('location', new Point(0, 0))
    ->exists(); // true
```
</details>


###  whereSrid

Filters records by the [ST_SRID](https://dev.mysql.com/doc/refman/8.0/en/gis-general-property-functions.html#function_st-srid) function.

| parameter name      | type
| ------------------  | -------------------- 
| `$column`           | `string`
| `$operator`         | `string`
| `$value`            | `int`

<details><summary>Example</summary>

```php
Place::create(['coordinates' => new Point(12.455363273620605, 41.90746728266806, 4326)]);

Place::query()
    ->whereSrid('coordinates', '=', 4326)
    ->exists(); // true
```
</details>

