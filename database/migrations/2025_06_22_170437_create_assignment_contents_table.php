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
        Schema::create('assignmentContents', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('userAssignmentID');
            $table->text('text')->nullable();
            $table->string('contentSID')->nullable();
            $table->integer('created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignmentContents');
    }
};
