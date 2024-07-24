<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestExtendedPlacesTable extends Migration
{
    public function up(): void
    {
        Schema::create('test_extended_places', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('address');
            $table->geometry('point', 'point')->nullable();
            $table->geometry('multi_point', 'multipoint')->nullable();
            $table->geometry('line_string', 'linestring')->nullable();
            $table->geometry('multi_line_string', 'multilinestring')->nullable();
            $table->geometry('polygon', 'polygon')->nullable();
            $table->geometry('multi_polygon', 'multipolygon')->nullable();
            $table->geometry('geometry_collection', 'geometrycollection')->nullable();
            $table->geometry('point_with_line_string_cast', 'point')->nullable();
            $table->decimal('longitude')->nullable();
            $table->decimal('latitude')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_extended_places');
    }
}
