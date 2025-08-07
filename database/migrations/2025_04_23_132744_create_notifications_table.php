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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('userID')->index();
            $table->string('title');
            $table->text('content')->nullable();
            $table->enum('type', ['info','success','warning','danger',])->default('info');
            $table->bigInteger('read')->nullable()->index();
            $table->bigInteger('created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
