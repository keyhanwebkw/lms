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
        Schema::create('cg_movies', function (Blueprint $table) {
            $table->id('ID');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('posterSID')->nullable();
            $table->text('description')->nullable();
            $table->enum('type',['series', 'film'])->index();
            $table->bigInteger('created');
            $table->bigInteger('updated');
            $table->bigInteger('archived')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cg_movies');
    }
};
