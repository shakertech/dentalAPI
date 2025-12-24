<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Appointments
        Schema::create('appointments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete(); // Doctor/Staff

            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('status')->default('scheduled'); // scheduled, confirmed, completed, cancelled, no-show
            $table->text('notes')->nullable();

            // Offline/Sync
            $table->string('device_id')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        // Visits (Session/Day)
        Schema::create('visits', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('patient_id')->constrained('patients')->cascadeOnDelete();
            // Optional: link to appointment if derived from one
            $table->foreignUlid('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();

            $table->date('date'); // The day of the visit
            $table->string('status')->default('in-progress'); // in-progress, completed

            // Offline/Sync
            $table->string('device_id')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        // Visit Entries (Ledger: Charges & Payments)
        Schema::create('visit_entries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('visit_id')->constrained('visits')->cascadeOnDelete();
            // Nullable treatment, only for charges
            $table->foreignUlid('treatment_id')->nullable()->constrained('treatments')->nullOnDelete();

            $table->string('entry_type'); // 'charge' or 'payment'
            $table->decimal('amount', 10, 2); // Positive value. Logic determines debit/credit based on type.
            $table->text('description')->nullable();
            $table->string('tooth_no')->nullable(); // For dental charts

            // Offline/Sync
            $table->string('device_id')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('patient_id')->constrained('patients')->cascadeOnDelete();
            // Can link to a specific visit or multiple visits. Let's link to a visit for now, or keep it loose.
            // Often invoices are for a specific visit.
            $table->foreignUlid('visit_id')->nullable()->constrained('visits')->nullOnDelete();

            $table->string('invoice_number')->nullable();
            $table->date('date');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('status')->default('draft'); // draft, sent, paid, partial, void

            // Offline/Sync
            $table->string('device_id')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('visit_entries');
        Schema::dropIfExists('visits');
        Schema::dropIfExists('appointments');
    }
};
