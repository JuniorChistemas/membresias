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
        Schema::create('daily_session_logs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Sesión');
            $table->decimal('price', 8, 2)->default(5.00);
            $table->foreignId('payment_method_id')->constrained()->onDelete('restrict');
            $table->timestamp('registered_at')->useCurrent(); // Fecha y hora del registro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_session_logs');
    }
};
