# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

```bash
# Start databases (required before running tests)
docker-compose up -d

# Run tests (pick one database)
composer pest:mysql
composer pest:mariadb
composer pest:postgres

# Run single test file
DB_CONNECTION=mysql DB_PORT=3307 ./vendor/bin/pest tests/Objects/PointTest.php

# Run single test by name
DB_CONNECTION=mysql DB_PORT=3307 ./vendor/bin/pest --filter="test name here"

# Run tests with coverage (requires XDEBUG)
composer pest-coverage:mysql

# Static analysis
composer phpstan

# Code formatting
composer pint
```

## Architecture

Laravel package for spatial data types. Supports MySQL 8, MariaDB 10, PostgreSQL 12-16 with PostGIS.

### Geometry Object Hierarchy (`src/Objects/`)

```
Geometry (abstract) - base class with WKT/WKB/GeoJSON serialization
├── Point - latitude/longitude with optional SRID
├── PointCollection (abstract)
│   ├── LineString (min 2 points)
│   └── MultiPoint (min 1 point)
└── GeometryCollection - base for collections, implements ArrayAccess
    ├── MultiLineString
    ├── Polygon (collection of LineStrings)
    └── MultiPolygon
```

### Key Components

- **`GeometryCast`** - Laravel cast that converts between DB WKB and PHP geometry objects
- **`Factory`** - Creates geometry objects from WKT/WKB/GeoJSON using `phayes/geophp` library
- **`HasSpatial` trait** - Adds spatial query scopes to models (whereWithin, whereContains, withDistance, etc.)
- **`EloquentSpatial`** - Static registry for custom geometry classes and default SRID
- **`AxisOrder`** - Handles MySQL 8 vs MariaDB differences for coordinate ordering
- **`Doctrine/`** - DBAL type mappings for Laravel < 11 migrations

### Data Flow

1. **Storage**: Geometry object → `GeometryCast::set()` → `ST_GeomFromText(WKT, SRID)` → DB
2. **Retrieval**: DB WKB → `GeometryCast::get()` → `Factory::parse()` → Geometry object

### Extending

Custom geometry classes: `EloquentSpatial::usePoint(CustomPoint::class)`
Macros: `Geometry::macro('name', fn)`
Default SRID: `EloquentSpatial::setDefaultSrid(Srid::WGS84)`
