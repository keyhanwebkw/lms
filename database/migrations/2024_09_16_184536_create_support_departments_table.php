<?php

use App\Models\SupportDepartment;
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
		Schema::create('supportDepartments', function (Blueprint $table) {
			$table->id('ID');
			$table->string('name', 255)->index();
			$table->string('slug', 255)->index();
			$table->enum('status', SupportDepartment::STATUS)->default('active')->index();
			$table->timestamp('created')->useCurrent();
			$table->timestamp('updated')->useCurrent()->useCurrentOnUpdate();
			$table->boolean('isArchived')->default(false)->index();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('supportDepartments');
	}
};
