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
		Schema::create('supportMessages', function (Blueprint $table) {
			$table->id('ID');
			$table->unsignedBigInteger('repliedMessageID')->default(0);
			$table->unsignedBigInteger('ticketID')->index();
			$table->unsignedBigInteger('userID');
			$table->unsignedBigInteger('responderUserID')->default(0);
			$table->text('message');
			$table->string('SID')->default('');
			$table->timestamp('date')->useCurrent();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('supportMessages');
	}
};
