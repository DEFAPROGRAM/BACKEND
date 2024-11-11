<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Esto crea una columna 'id' como clave primaria
            $table->string('nombres', 50);
            $table->string('apellidos', 50);
            $table->string('cargo', 50);
            $table->unsignedBigInteger('id_sede');
            $table->unsignedBigInteger('id_juzgado');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_sede')->references('id_sede')->on('sedes')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('id_juzgado')->references('id_juzgado')->on('juzgados')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};