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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id('ID');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('contentSID')->nullable();
            $table->bigInteger('deadline')->nullable();
            $table->unsignedInteger('score');
            $table->unsignedInteger('minScoreToPass');
            $table->boolean('isRequired')->default(true);
            $table->Integer('retryCount')->default(1);
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
        Schema::dropIfExists('assignments');
    }
};
