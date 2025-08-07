<?php

use App\Models\SupportTicket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('supportTickets', function (Blueprint $table) {
			$table->id('ID');
			$table->unsignedBigInteger('departmentID');
			$table->unsignedBigInteger('userID')->index();
			$table->unsignedBigInteger('userLastMessageID')->default(0);
			$table->unsignedBigInteger('adminLastMessageID')->default(0);
			$table->unsignedBigInteger('lastResponderUserID')->default(0);
			$table->enum('status', SupportTicket::STATUS)->default('open')->index();
			$table->bigInteger('updated');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('supportTickets');
	}
};
