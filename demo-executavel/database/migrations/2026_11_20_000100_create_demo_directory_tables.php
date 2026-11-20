<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('consultants')->nullOnDelete();
            $table->string('external_code')->unique();
            $table->string('full_name');
            $table->string('profile');
            $table->string('regional')->default('Regional Demonstração');
            $table->unsignedInteger('personal_production_count')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['profile', 'active']);
        });

        Schema::create('associates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('consultant_id')->constrained()->cascadeOnDelete();
            $table->string('external_code')->unique();
            $table->string('full_name');
            $table->string('status');
            $table->timestamps();

            $table->index(['status', 'consultant_id']);
        });

        Schema::create('productions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('consultant_id')->constrained()->cascadeOnDelete();
            $table->string('external_code')->unique();
            $table->string('contract_code')->unique();
            $table->string('competence', 7);
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('bonus_amount', 12, 2);
            $table->string('status')->default('eligible');
            $table->timestamps();

            $table->index(['competence', 'status']);
        });

        Schema::create('unpaid_bills', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('associate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('consultant_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->date('due_date');
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending');
            $table->boolean('has_pdf')->default(true);
            $table->timestamps();

            $table->index(['status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unpaid_bills');
        Schema::dropIfExists('productions');
        Schema::dropIfExists('associates');
        Schema::dropIfExists('consultants');
    }
};
