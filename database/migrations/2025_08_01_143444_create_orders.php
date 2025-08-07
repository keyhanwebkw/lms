<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('ID');

            $table->unsignedBigInteger('userID')->index();
            $table->string('userIp', 50);

            $table->unsignedInteger('itemsCount')->default(0);
            $table->string('currency', 3)->default('IRR');

            $table->unsignedBigInteger('subtotalAmount')->default(0); // مجموع مبلغ‌ها قبل از تخفیف
            $table->unsignedBigInteger('productsDiscountAmount')->default(0); // مجموع تخفیف‌های محصولات
            $table->unsignedBigInteger('codeDiscountAmount')->default(0); // تخفیف با کد
            $table->unsignedBigInteger('totalDiscountAmount')->default(0); // مجموع تخفیف کل = محصولات + کد
            $table->unsignedBigInteger('shippingAmount')->default(0); // هزینه ارسال

            $table->unsignedBigInteger('payByBalance')->default(0); // پرداخت با موجودی
            $table->unsignedBigInteger('totalPayableAmount')->default(0); // مبلغ قابل پرداخت نهایی

            $table->unsignedBigInteger('discountCodeID')->default(0);
            $table->string('discountCode', 20)->nullable();

            $table->enum('status', ['pending', 'waitingForPayment', 'confirm', 'cancel', 'refund', 'deliver', 'complete'])->default('pending');
            $table->enum('paymentStatus', ['waitingForPayment', 'paid', 'refunded'])->default('waitingForPayment');
            $table->enum('paymentType', ['cash', 'installment'])->default('cash');
            $table->enum('productType', ['physical', 'virtual', 'both'])->default('virtual');

            $table->bigInteger('shippingType')->nullable();
            $table->bigInteger('addressID')->default(0);
            $table->unsignedInteger('weight')->default(0);

            $table->text('description')->nullable();
            $table->text('adminDescription')->nullable();

            $table->bigInteger('deliveryDate')->default(0);
            $table->bigInteger('created')->useCurrent();
            $table->bigInteger('updated')->useCurrent();

            $table->index(['userID', 'status']);
            $table->index(['paymentStatus']);
            $table->index(['totalPayableAmount']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
