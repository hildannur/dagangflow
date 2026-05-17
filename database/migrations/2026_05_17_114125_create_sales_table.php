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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('channel');
            $table->integer('quantity')->default(1);
            $table->integer('selling_price')->default(0);
            $table->integer('gross_total')->default(0);
            $table->integer('platform_fee')->default(0);
            $table->integer('net_total')->default(0);
            $table->string('status')->default('Selesai');
            $table->text('note')->nullable();
            $table->date('sale_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
