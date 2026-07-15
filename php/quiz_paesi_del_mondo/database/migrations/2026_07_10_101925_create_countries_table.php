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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('alpha2', 2)->unique();
            $table->string('alpha3', 3)->unique();
            $table->string('name');
            $table->string('name_it');
            $table->string('capital');
            $table->string('region');
            $table->string('subregion')->nullable();
            $table->unsignedBigInteger('population');
            $table->jsonb('languages');
            $table->jsonb('currencies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
