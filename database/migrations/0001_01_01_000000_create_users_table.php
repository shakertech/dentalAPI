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
        // Tenants Table
        Schema::create('tenants', function (Blueprint $table) {
            $table->ulid('id')->primary(); // ULID Primary Key
            $table->string('name'); // Clinic Name
            $table->string('title')->nullable(); // Clinic Display Title
            $table->text('description')->nullable(); // About the clinic/doctor

            $table->string('doctor_name')->nullable();
            $table->string('doctor_qualification')->nullable(); // e.g. BDS, MDS
            $table->string('doctor_specialty')->nullable(); // e.g. Orthodontist

            $table->string('domain')->unique()->nullable(); // Custom domain if needed
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Subscription System
            $table->string('subscription_plan')->default('monthly'); // monthly, biannual, yearly
            $table->string('subscription_status')->default('active'); // active, expired, cancelled
            $table->date('subscription_ends_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Users Table (Modified to include tenant_id)
        Schema::create('users', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Multi-tenancy
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('staff'); // dentist, assistant, receptionist

            // Offline/Sync fields
            $table->string('device_id')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Password Reset Tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions (Standard Laravel)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('tenants');
    }
};
