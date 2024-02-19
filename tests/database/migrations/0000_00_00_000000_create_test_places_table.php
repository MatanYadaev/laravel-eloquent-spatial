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
      $table->point('point')->projection(0)->nullable();
      $table->multiPoint('multi_point')->projection(0)->nullable();
      $table->lineString('line_string')->projection(0)->nullable();
      $table->multiLineString('multi_line_string')->projection(0)->nullable();
      $table->polygon('polygon')->projection(0)->nullable();
      $table->multiPolygon('multi_polygon')->projection(0)->nullable();
      $table->geometryCollection('geometry_collection')->projection(0)->nullable();
      $table->point('point_with_line_string_cast')->projection(0)->nullable();
      $table->decimal('longitude')->nullable();
      $table->decimal('latitude')->nullable();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('test_places');
  }
}
