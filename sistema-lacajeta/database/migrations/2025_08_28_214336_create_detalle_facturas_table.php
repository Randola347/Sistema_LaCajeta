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
        Schema::create('detalle_facturas', function (Blueprint $table) {
            $table->id();

            // Clave foránea hacia facturas
            $table->foreignId('factura_id')->constrained('facturas')->onDelete('cascade');

            // Clave foránea hacia productos
            $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');

            // Cantidad y precio del producto en la línea
            $table->decimal('cantidad', 8, 2);
            $table->decimal('precio_unitario', 10, 2);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_facturas');
    }
};
