<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'platform_db';

    public function up(): void
    {
        Schema::create('tenant_user', function (Blueprint $table) {
            // Foreign key to identity.users
            $table->foreignId('user_id')
                ->constrained(config('database.connections.identity_db.database') . '.users');

            // Foreign key to platform.tenants
            $table->foreignId('tenant_id')->constrained('tenants');

            $table->string('role')->default('member'); // e.g., 'admin', 'editor'
            $table->timestamps();

            // A user can only be in a tenant once
            $table->primary(['user_id', 'tenant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_user');
    }
};