<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('storage', function (Blueprint $table) {
            $table->string('SID')->primary();
            $table->unsignedBigInteger('userID')->index();
            $table->unsignedBigInteger('morphable_id')->nullable();
            $table->string('morphable_type')->nullable();
            $table->string('extension', 10);
            $table->string('fileName');
            $table->integer('fileSize')->unsigned();
            $table->enum('fileType', ['audio', 'image', 'excel', 'pdf','video']);
            $table->string('additionalPath')->nullable();
            $table->integer('width')->unsigned()->nullable();
            $table->integer('height')->unsigned()->nullable();
            $table->integer('duration')->unsigned()->nullable();
            $table->boolean('isUsed')->default(false);
            $table->boolean('isPublic')->default(false);
            $table->bigInteger('created');
            $table->bigInteger('updated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('storage');
    }
};
