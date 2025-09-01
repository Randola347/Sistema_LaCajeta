<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->integer('inventario');
            $table->unsignedInteger('proveedor_id'); // tipo igual a proveedores.id
            $table->timestamps();

            $table->foreign('proveedor_id')
                ->references('id')
                ->on('proveedores')
                ->onDelete('cascade');
        });
    }



    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
