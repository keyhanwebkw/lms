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
        Schema::create('articles', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('managerID');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('introduction');
            $table->text('content');
            $table->string('posterSID')->nullable();
            $table->smallInteger('readingTime');
            $table->string('language')->index();
            $table->string('metaTitle')->nullable();
            $table->string('metaKeyword')->nullable();
            $table->text('metaDescription')->nullable();
            $table->biginteger('created');
            $table->biginteger('updated');
            $table->biginteger('archived')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
