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
        // Patients Table
        Schema::create('patients', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();

            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('gender')->nullable(); // 'male', 'female', 'other'
            $table->integer('age')->nullable(); // For when DOB is unknown or direct entry
            $table->string('weight')->nullable(); // e.g. 70kg
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->text('medical_history')->nullable(); // JSON or Text

            // Offline/Sync
            $table->string('device_id')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        // Treatments (Catalogue/Service List)
        Schema::create('treatments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();

            $table->string('name');
            $table->string('code')->nullable(); // CPT code or internal code
            $table->decimal('default_cost', 10, 2)->default(0);
            $table->decimal('default_price', 10, 2)->default(0);

            // Offline/Sync
            $table->string('device_id')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        // Medicines
        Schema::create('medicines', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();

            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('dosage_form')->nullable(); // Tablet, Syrup, etc.
            $table->string('strength')->nullable(); // 500mg

            // Offline/Sync
            $table->string('device_id')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        // Prescriptions
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('patient_id')->constrained('patients')->cascadeOnDelete();
            // We will link visit_id later in a comprehensive migration or add it here if Visit is created first. 
            // Since we haven't created visits table yet, I will add the column without constraint OR use text and index.
            // Better practice: Create visits table first or add foreign key later. 
            // I'll add foreign key later or just create the column now. I'll assume Visits is next and just put the column.
            $table->ulid('visit_id')->nullable()->index();

            $table->date('date');
            $table->text('notes')->nullable();

            // Offline/Sync
            $table->string('device_id')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        // Prescription Items
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('prescription_id')->constrained('prescriptions')->cascadeOnDelete();
            $table->foreignUlid('medicine_id')->constrained('medicines')->cascadeOnDelete();

            $table->string('dosage')->nullable(); // e.g. "1-0-1"
            $table->string('duration')->nullable(); // e.g. "5 days"
            $table->string('instruction')->nullable(); // e.g. "After food"

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
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('patients');
    }
};
