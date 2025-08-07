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
        Schema::create('comments', function (Blueprint $table) {
            $table->id('ID');
            $table->string('commentable_type')->index();
            $table->unsignedBigInteger('commentable_id')->index();
            $table->unsignedBigInteger('userID')->nullable();
            $table->unsignedBigInteger('managerID')->nullable();
            $table->unsignedBigInteger('parentID')->nullable();
            $table->text('content');
            $table->boolean('hasReply')->default(false);
            $table->enum('status', ['pending','approved','rejected'])->default('pending')->index();
            $table->bigInteger('created');
            $table->bigInteger('updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
