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
        Schema::table('cg_seasonEpisodes', function (Blueprint $table) {
           $table->string('videoSID')->nullable()->change();
           $table->string('title')->nullable()->change();
           $table->string('videoSID')->nullable()->change();
           $table->string('sortOrder')->nullable()->change();
           $table->string('videoUrl')->nullable()->after('videoSID');
           $table->dropColumn('archived');
           $table->biginteger('deleted')->nullable()->index()->after('updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cg_seasonEpisodes', function (Blueprint $table) {
            $table->string('videoSID')->nullable(false)->change();
            $table->string('title')->nullable(false)->change();
            $table->string('videoSID')->nullable(false)->change();
            $table->string('sortOrder')->nullable(false)->change();
            $table->dropColumn('videoUrl');
            $table->dropColumn('deleted');
            $table->bigInteger('archived')->nullable()->index()->after('updated');
        });
    }
};
