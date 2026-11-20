<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_lots', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('financial_period_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('status')->default('open');
            $table->unsignedInteger('item_count')->default(0);
            $table->unsignedInteger('production_count')->default(0);
            $table->decimal('gross_amount', 14, 2)->default(0);
            $table->decimal('bonus_amount', 14, 2)->default(0);
            $table->decimal('adjustment_amount', 14, 2)->default(0);
            $table->decimal('official_amount', 14, 2)->default(0);
            $table->string('checksum', 64)->nullable();
            $table->unsignedInteger('lock_version')->default(1);
            $table->foreignId('closed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('closed_at')->nullable();
            $table->text('close_reason')->nullable();
            $table->timestamps();

            $table->index(['financial_period_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_lots');
    }
};
