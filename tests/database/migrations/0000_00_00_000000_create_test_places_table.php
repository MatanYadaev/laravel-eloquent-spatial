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
            ;
            $table->string('name');
            $table->string('address');
            $table->point('location');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_addresses');
    }
}
