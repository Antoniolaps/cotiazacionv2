<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->enum('tipo_consumidor', ['consumidor_final', 'juridico'])
                  ->default('consumidor_final')
                  ->after('estado');
            $table->string('punto_facturacion', 30)->nullable()->after('tipo_consumidor');
            $table->string('dv', 10)->nullable()->after('punto_facturacion'); // dígito verificador RUC
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['tipo_consumidor', 'punto_facturacion', 'dv']);
        });
    }
};
