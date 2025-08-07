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
        Schema::table('sectionEpisodes', function (Blueprint $table): void {
            $table->dropColumn('examRelationID');
            $table->unsignedBigInteger('examID')->nullable()->after('courseSectionID');
            $table->unsignedBigInteger('assignmentID')->nullable()->after('examID');
            $table->unsignedBigInteger('episodeContentID')->nullable()->after('assignmentID');
            $table->boolean('isMandatory')->default(false)->after('episodeContentID');
            $table->enum('status',['draft','published','archived'])->default('draft')->index()->after('isMandatory');
            $table->smallInteger('sortOrder')->default(1)->change();
            $table->dropColumn('title');
            $table->dropColumn('duration');
            $table->dropColumn('description');
            $table->dropColumn('contentSID');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sectionEpisodes', function (Blueprint $table) {
            $table->bigInteger('examRelationID')->nullable();
            $table->dropColumn('examID');
            $table->dropColumn('assignmentID');
            $table->dropColumn('episodeContentID');
            $table->dropColumn('isMandatory');
            $table->dropColumn('status');
            $table->smallInteger('sortOrder')->default(0)->change();
            $table->string('title')->nullable()->after('courseSectionID');
            $table->text('description')->nullable()->after('title');
            $table->string('duration')->nullable()->after('description');
            $table->string('contentSID')->nullable()->after('duration');
        });
    }
};
