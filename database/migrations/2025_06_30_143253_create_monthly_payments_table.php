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
        Schema::create('monthly_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_membership_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained()->onDelete('cascade');
            $table->foreignId('coach_id')->constrained()->onDelete('cascade');

            $table->unsignedTinyInteger('months_total'); // Total de meses
            $table->decimal('price', 6, 2); // Precio individual de la membresía
            $table->decimal('total', 8, 2); // Total pagado (price * months_total)

            $table->date('date_registration'); // Fecha de registro
            $table->boolean('status')->default(true); // Activo/Inactivo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_payments');
    }
};
