<?php

// database/migrations/2025_08_30_000000_alter_facturas_total_default.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Asegura que no haya NULL previos
        DB::statement("UPDATE facturas SET total = 0 WHERE total IS NULL");

        Schema::table('facturas', function (Blueprint $table) {
            // cambia según tu tipo actual (decimal(10,2) es típico)
            $table->decimal('total', 10, 2)->default(0)->change();
        });
    }
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->decimal('total', 10, 2)->default(null)->change();
        });
    }

};
