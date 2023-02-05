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
      $table->point('point')->nullable();
      $table->multiPoint('multi_point')->nullable();
      $table->lineString('line_string')->nullable();
      $table->multiLineString('multi_line_string')->nullable();
      $table->polygon('polygon')->nullable();
      $table->multiPolygon('multi_polygon')->nullable();
      $table->geometryCollection('geometry_collection')->nullable();
      $table->point('point_with_line_string_cast')->nullable();
      $table->decimal('longitude')->nullable();
      $table->decimal('latitude')->nullable();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('test_places');
  }
}
