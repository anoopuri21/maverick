<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            // Unguessable public reference used in the payment URL.
            $table->string('token', 64)->unique();
            $table->string('student_name');
            $table->string('student_email');
            $table->string('student_phone')->nullable();
            $table->string('program_title');
            $table->string('currency', 3)->default('AED');
            // Whole plan value and the per-installment amount, in minor units.
            $table->unsignedBigInteger('total_minor');
            $table->unsignedBigInteger('installment_minor');
            $table->unsignedTinyInteger('installment_count');
            $table->date('first_due_date');
            $table->string('status', 20)->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'first_due_date']);
        });

        Schema::create('payment_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_plan_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('sequence');
            $table->unsignedBigInteger('amount_minor');
            $table->date('due_date');
            $table->string('status', 20)->default('pending');
            $table->dateTime('paid_at')->nullable();
            // How the money arrived: card, bank transfer, cash, cheque.
            $table->string('paid_via', 20)->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();

            $table->unique(['payment_plan_id', 'sequence']);
            $table->index(['payment_plan_id', 'status']);
        });

        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_installment_id')->nullable()
                ->constrained('payment_installments')->nullOnDelete();
            $table->string('gateway', 30);
            // Provider identifiers: unique so a replayed webhook cannot double-pay.
            $table->string('gateway_reference')->nullable()->unique();
            $table->string('gateway_session_id')->nullable()->index();
            $table->unsignedBigInteger('amount_minor');
            $table->string('currency', 3)->default('AED');
            $table->string('status', 20)->default('pending');
            $table->json('payload')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->index(['payment_plan_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('payment_installments');
        Schema::dropIfExists('payment_plans');
    }
};
