<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faqsCategories', function (Blueprint $table) {
            $table->id('ID');
            $table->string('title')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('morphable_id')->nullable();
            $table->string('morphable_type')->nullable();
            $table->string('language')->index()->nullable();
            $table->smallInteger('sortOrder')->default(1);
            $table->bigInteger('archived')->nullable();
            $table->bigInteger('created');
            $table->bigInteger('updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqsCategories');
    }
};
