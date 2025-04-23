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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('order_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignUuid('product_id')
                ->constrained()
                ->onDelete('cascade');
            $table->integer('order_quantity');
            $table->float('product_price', 12, 2);
            $table->timestamp('date_placed')->nullable();
            $table->timestamp('date_accepted')->nullable();
            $table->timestamp('date_shipped')->nullable();
            $table->timestamp('date_delivered')->nullable();
            $table->enum('status', ['pending', 'accepted', 'shipped', 'delivered', 'cancelled']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
