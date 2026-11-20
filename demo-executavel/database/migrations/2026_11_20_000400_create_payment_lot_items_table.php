<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_lot_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payment_lot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('consultant_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('production_count');
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('bonus_amount', 12, 2);
            $table->decimal('adjustment_amount', 12, 2)->default(0);
            $table->decimal('official_amount', 12, 2);
            $table->string('status')->default('open');
            $table->json('snapshot');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->unique(['payment_lot_id', 'consultant_id']);
            $table->index(['payment_lot_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_lot_items');
    }
};
