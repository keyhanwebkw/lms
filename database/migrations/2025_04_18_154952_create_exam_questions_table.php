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
        Schema::create('examQuestions', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('examID')->index();
            $table->unsignedBigInteger('questionID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examQuestions');
    }
};
