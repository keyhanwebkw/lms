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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id('ID');
            $table->string('name');
            $table->string('family');
            $table->string('mobile');
            $table->string('email');
            $table->text('biography')->nullable();
            $table->string('avatarSID')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->bigInteger('birthDate')->unsigned()->nullable();
            $table->bigInteger('startEducationDate')->unsigned()->nullable();
            $table->bigInteger('startTeachingDate')->unsigned()->nullable();
            $table->bigInteger('coursesCount')->unsigned()->default(0);
            $table->bigInteger('attendeesCount')->unsigned()->default(0);
            $table->tinyInteger('rating')->unsigned()->nullable();
            $table->string('linkedinProfile')->nullable();
            $table->string('telegramUsername')->nullable();
            $table->string('website')->nullable();
            $table->bigInteger('created');
            $table->bigInteger('updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
