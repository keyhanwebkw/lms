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
        Schema::create('userCourses', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->unsignedBigInteger('courseID');
            $table->unsignedBigInteger('userID');
            $table->enum('status', ['active', 'completed'])->default('active');
            $table->bigInteger('created')->useCurrent();
            $table->bigInteger('updated')->useCurrent();
            $table->index(['courseID', 'userID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userCourses');
    }
};
