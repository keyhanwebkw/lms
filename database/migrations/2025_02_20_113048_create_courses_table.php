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
        Schema::create('courses', function (Blueprint $table) {
            $table->id('ID');
            $table->string('name');
            $table->text('description');
            $table->integer('duration');
            $table->enum('type', ['audio', 'video'])->index();
            $table->bigInteger('price')->index()->nullable();
            $table->bigInteger('discountAmount')->nullable()->default(0);
            $table->integer('participants')->default(0);
            $table->integer('participantLimitation')->default(0);
            $table->enum('status', ['ongoing', 'completed', 'upcoming'])->index();
            $table->integer('score')->default(1);
            $table->bigInteger('teacherID');
            $table->string('slug')->unique();
            $table->enum('level', ['basic', 'intermediate', 'advanced'])->default('intermediate');
            $table->bigInteger('startDate')->index();
            $table->bigInteger('endDate')->index()->nullable();
            $table->bigInteger('managerID')->nullable();
            $table->bigInteger('created');
            $table->bigInteger('updated');
            $table->bigInteger('archived')->index()->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
