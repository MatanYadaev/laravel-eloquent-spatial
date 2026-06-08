<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestPlacesTable extends Migration
{
    public function up(): void
    {
        Schema::create('test_places', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('address');
            $table->geometry('geometry')->nullable();
            $table->point('point')->isGeometry()->nullable();
            $table->multiPoint('multi_point')->isGeometry()->nullable();
            $table->lineString('line_string')->isGeometry()->nullable();
            $table->multiLineString('multi_line_string')->isGeometry()->nullable();
            $table->polygon('polygon')->isGeometry()->nullable();
            $table->multiPolygon('multi_polygon')->isGeometry()->nullable();
            $table->geometryCollection('geometry_collection')->isGeometry()->nullable();
            $table->point('point_with_line_string_cast')->isGeometry()->nullable();
            $table->point('point_geography')->nullable();
            $table->decimal('longitude')->nullable();
            $table->decimal('latitude')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_places');
    }
}
