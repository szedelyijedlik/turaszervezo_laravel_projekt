<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string("destination", 100);
            $table->text("description")->nullable();
            $table->date("starts_on");
            $table->unsignedInteger("days");
            $table->string("difficulty", 10);
            $table->unsignedInteger("price_per_person");
            $table->unsignedInteger("free_places")->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
