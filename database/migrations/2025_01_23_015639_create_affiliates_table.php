<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('password');
            $table->text('address')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_id')->nullable()->constrained('banks')->delete('cascade');
            $table->string('account_name')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_rejected')->default(false);
            $table->boolean('is_wp_affiliate')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
