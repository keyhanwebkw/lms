<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orderDetails', function (Blueprint $table) {
            $table->id('ID');

            $table->unsignedBigInteger('orderID')->index();
            $table->unsignedBigInteger('productID')->nullable()->index();
            $table->unsignedBigInteger('courseID')->nullable()->index();

            $table->unsignedInteger('quantity')->default(1);

            $table->unsignedBigInteger('unitPrice')->default(0); // قیمت واحد بدون تخفیف و مالیات
            $table->unsignedBigInteger('unitPriceWithDiscount')->default(0); // قیمت واحد با تخفیف

            $table->unsignedBigInteger('totalAmount')->default(0); // مبلغ کل قبل از تخفیف
            $table->unsignedBigInteger('totalAmountWithDiscount')->default(0); // مبلغ کل با تخفیف
            $table->unsignedBigInteger('discountAmount')->default(0); // مقدار کل تخفیف روی این ردیف

            $table->enum('type', ['physical', 'virtual'])->default('virtual'); // نوع آیتم: physical / virtual

            $table->bigInteger('created')->useCurrent();
            $table->bigInteger('updated')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orderDetails');
    }
};
