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
        Schema::create('coursesCategories', function (Blueprint $table) {
            $table->id('ID');
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->smallInteger('sortOrder')->default(1);
            $table->string('photoSID')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->string('metaTitle')->nullable();
            $table->text('metaDescription')->nullable();
            $table->string('metaKeyword')->nullable();
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
        Schema::dropIfExists('coursesCategories');
    }
};
