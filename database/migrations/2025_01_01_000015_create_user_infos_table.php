<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userInfos', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('userID')->unique();
            $table->string('pictureSID')->nullable();
            $table->text('biography')->nullable();
            $table->json('socialMedia')->nullable();
            $table->json('extraInfo')->nullable();
            $table->bigInteger('created')->nullable();
            $table->bigInteger('updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userInfos');
    }
};
