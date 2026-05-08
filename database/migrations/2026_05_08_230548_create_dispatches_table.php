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
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('depot_id')->constrained();
            $table->foreignId('tank_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('tanker_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->decimal('volume', 15, 2);
            $table->date('dispatch_date');
            $table->string('waybill_no')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatches');
    }
};
