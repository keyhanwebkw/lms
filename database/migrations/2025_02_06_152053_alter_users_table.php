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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['parent', 'child', 'consultant'])->default('parent')->after('mobile');
            $table->integer('parentID')->nullable()->after('type');
            $table->string('username')->nullable()->after('parentID');
            $table->string('mobile', 20)->nullable()->change();
            $table->unsignedSmallInteger('countryCode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('parentID');
            $table->dropColumn('username');
        });
    }
};
