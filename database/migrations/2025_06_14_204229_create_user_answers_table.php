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
        Schema::create('userAnswers', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('userExamID')->index();
            $table->unsignedBigInteger('questionID');
            $table->unsignedBigInteger('optionID')->nullable();
            $table->bigInteger('created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userAnswers');
    }
};
