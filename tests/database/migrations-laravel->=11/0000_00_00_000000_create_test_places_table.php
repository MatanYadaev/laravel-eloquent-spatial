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
            $table->geometry('geometry', subtype: 'geometry')->nullable();
            $table->geometry('point', subtype: 'point')->nullable();
            $table->geometry('multi_point', subtype: 'multipoint')->nullable();
            $table->geometry('line_string', subtype: 'linestring')->nullable();
            $table->geometry('multi_line_string', subtype: 'multilinestring')->nullable();
            $table->geometry('polygon', subtype: 'polygon')->nullable();
            $table->geometry('multi_polygon', subtype: 'multipolygon')->nullable();
            $table->geometry('geometry_collection', subtype: 'geometrycollection')->nullable();
            $table->geometry('point_with_line_string_cast', subtype: 'point')->nullable();
            $table->decimal('longitude')->nullable();
            $table->decimal('latitude')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_places');
    }
}
