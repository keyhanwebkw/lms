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
    public function up()
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::create('personalAccessTokens', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('affectorUserID')->default(0);
            $table->string("tokenable_type", 100);
            $table->unsignedBigInteger("tokenable_id");
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created')->useCurrent();
            $table->timestamp('updated')->useCurrent()->useCurrentOnUpdate();

            // Index
            $table->index(["tokenable_type", "tokenable_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personalAccessTokens');
        Schema::dropIfExists('personal_access_tokens');
    }
};
