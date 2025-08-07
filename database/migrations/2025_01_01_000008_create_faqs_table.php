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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('categoryID')->nullable();
            $table->text('question');
            $table->text('answer');
            $table->string('language')->index()->nullable();
            $table->string('metaTitle')->nullable();
            $table->text('metaDescription')->nullable();
            $table->string('metaKeyword')->nullable();
            $table->integer('sortOrder')->default(1);
            $table->bigInteger('created');
            $table->bigInteger('updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
