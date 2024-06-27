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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('number', 32)->unique();
            $table->date('delivery_date')->nullable();
            $table->string('status')->nullable();
            $table->decimal('subtotal')->nullable();
            $table->string('shipping_method')->nullable();
            $table->decimal('shipping_price')->nullable();
            $table->integer('margin')->nullable();
            $table->integer('tax')->nullable();
            $table->decimal('total_price')->nullable();
            $table->string('currency')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
