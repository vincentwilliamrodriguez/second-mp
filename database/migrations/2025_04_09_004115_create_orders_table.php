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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('display_name')
                ->storedAs("CONCAT('ORD-', UPPER(LEFT(id, 8)))");
            $table->foreignId('customer_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('address');
            $table->string('barangay');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->string('delivery_method');
            $table->string('payment_method');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('shipping_fee', 15, 2);
            $table->decimal('tax', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
