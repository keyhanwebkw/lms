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
        Schema::create('courseSections', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('courseID')->index();
            $table->bigInteger('examRelationID')->nullable();
            $table->string('title');
            $table->smallInteger('sortOrder')->default(0);
            $table->bigInteger('created');
            $table->bigInteger('updated');
            $table->bigInteger('archived')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courseSections');
    }
};
