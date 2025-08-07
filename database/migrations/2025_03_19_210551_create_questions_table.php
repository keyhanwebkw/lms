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
        Schema::create('questions', function (Blueprint $table) {
            $table->id('ID');
            $table->text('question')->nullable();
            $table->string('contentSID')->nullable();
            $table->enum('questionDifficultyLevel', ['easy', 'medium', 'hard'])->default('medium');
            $table->integer('timeLimit')->default(0);
            $table->integer('score')->default(0);
            $table->integer('sortOrder');
            $table->bigInteger('managerID');
            $table->bigInteger('created');
            $table->bigInteger('updated');
            $table->bigInteger('archived')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
