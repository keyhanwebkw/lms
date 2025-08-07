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
        Schema::create('exams', function (Blueprint $table) {
            $table->id('ID');
            $table->string('title');
            $table->text('description')->nullable();
            $table->bigInteger('startDate');
            $table->bigInteger('endDate');
            $table->smallInteger('score')->default(0);
            $table->smallInteger('duration')->default(0);
            $table->integer('minScoreToPass')->default(0);
            $table->integer('retryAttempts')->default(1);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->bigInteger('managerID');
            $table->bigInteger('created');
            $table->bigInteger('updated');
            $table->index(['startDate', 'endDate']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
