<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'platform_db';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->create('tenant_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // References identity_db.users.id
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('role')->default('member'); // admin, editor, member, etc.
            $table->timestamps();

            $table->unique(['user_id', 'tenant_id']);
            $table->index('tenant_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('tenant_user');
    }
};
