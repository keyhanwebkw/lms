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
        Schema::create('settings', function (Blueprint $table) {
            $table->id('ID');
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->enum('type',['string', 'integer', 'json', 'boolean', 'mixed']);
            $table->string('relatedTo')->index();
            $table->integer('limit')->nullable();
            $table->bigInteger('updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
