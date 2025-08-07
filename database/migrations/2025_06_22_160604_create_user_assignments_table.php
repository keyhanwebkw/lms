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
        Schema::create('userAssignments', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('assignmentID')->index();
            $table->unsignedBigInteger('userID')->index();
            $table->text('managerResponse')->nullable();
            $table->enum('status', ['inProgress','pending', 'accepted', 'rejected', 'resubmitted'])->default('inProgress');
            $table->unsignedInteger('receivedScore')->default(0);
            $table->unsignedSmallInteger('retryCount')->default(0);
            $table->bigInteger('created');
            $table->bigInteger('updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userAssignments');
    }
};
