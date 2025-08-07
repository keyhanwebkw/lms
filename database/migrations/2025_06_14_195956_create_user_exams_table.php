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
        Schema::create('userExams', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('examID')->index();
            $table->unsignedBigInteger('userID')->index();
            $table->enum('examStatus', ['notStarted', 'inProgress', 'failed', 'passed'])->default(
                'notStarted'
            )->index();
            $table->tinyInteger('retryCount')->default(1);
            $table->integer('score')->default(0);
            $table->smallinteger('trueAnswers')->default(0);
            $table->smallinteger('falseAnswers')->default(0);
            $table->smallinteger('skippedAnswers')->default(0);
            $table->biginteger('created');
            $table->biginteger('updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userExams');
    }
};
