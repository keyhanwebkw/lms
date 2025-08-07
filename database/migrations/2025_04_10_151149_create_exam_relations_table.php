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
        Schema::create('examRelations', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('examID');
            $table->unsignedBigInteger('courseID')->index()->nullable();
            $table->unsignedBigInteger('courseSectionID')->index()->nullable();
            $table->unsignedBigInteger('sectionEpisodeID')->index()->nullable();
            $table->unsignedBigInteger('assignmentID')->index()->nullable();
            $table->boolean('isMandatory')->default(false);
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
        Schema::dropIfExists('examRelations');
    }
};
