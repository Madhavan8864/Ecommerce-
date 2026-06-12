<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');

            // transaction id – UNIQUE ONLY ONCE
            $table->string('transaction_id')->nullable()->unique();

            $table->decimal('amount', 10, 2);

            $table->enum('payment_method', [
                'cod', 'card', 'paypal', 'stripe', 'razorpay'
            ]);

            $table->string('payment_gateway')->nullable();

            $table->enum('status', [
                'pending',
                'completed',
                'failed',
                'refunded',
                'partially_refunded'
            ])->default('pending');

            $table->json('payment_details')->nullable();
            $table->text('failure_reason')->nullable();

            $table->decimal('refund_amount', 10, 2)->default(0);
            $table->text('refund_reason')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->string('currency', 3)->default('INR');

            $table->timestamps();

            // foreign key
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');

            // indexes (NO duplicate unique)
            $table->index('order_id');
            $table->index('status');
            $table->index('payment_method');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
