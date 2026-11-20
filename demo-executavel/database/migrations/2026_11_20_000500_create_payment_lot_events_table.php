<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_lot_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payment_lot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type');
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->text('description');
            $table->json('context')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['payment_lot_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_lot_events');
    }
};
