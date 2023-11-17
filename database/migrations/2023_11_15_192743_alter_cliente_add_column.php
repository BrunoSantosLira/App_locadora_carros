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
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('CPF',14)->after('nome');
            $table->text('endereço');
            $table->string('email')->unique();
            $table->integer('telefone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('telefone');
            $table->dropColumn('email');
            $table->dropColumn('endereço');
            $table->dropColumn('CPF');
        });
    }
};
