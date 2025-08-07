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
        Schema::create('cg_moviesCategories', function (Blueprint $table) {
            $table->id('ID');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('photoSID')->nullable();
            $table->text('description')->nullable();
            $table->smallInteger('sortOrder');
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
        Schema::dropIfExists('cg_moviesCategories');
    }
};
