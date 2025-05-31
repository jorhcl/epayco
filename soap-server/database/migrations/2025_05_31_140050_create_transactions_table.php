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
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->foreignId('wallet_id')
                ->constrained('wallets')
                ->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('description', 255)->nullable();
            $table->string('token', 6)->unique()->nullable();
            $table->string('session_id', 255)->nullable();
            $table->string('status', 255)->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
