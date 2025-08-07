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
        Schema::create('courseIntros', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('courseID')->index();
            $table->enum('type',['banner','introVideo']);
            $table->string('SID')->nullable();
            $table->text('url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courseIntros');
    }
};
