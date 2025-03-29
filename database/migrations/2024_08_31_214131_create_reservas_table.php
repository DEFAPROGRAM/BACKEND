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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id('id_reserva');
            $table->unsignedBigInteger('id_sala')->nullable();
            $table->unsignedBigInteger('id_juzgado')->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->text('observaciones')->nullable();
            $table->string('estado', 50)->default('pendiente');
            $table->timestamps();

            $table->foreign('id_sala')->references('id_sala')->on('salas')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('id_juzgado')->references('id_juzgado')->on('juzgados')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};