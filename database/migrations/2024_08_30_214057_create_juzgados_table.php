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
        Schema::create('juzgados', function (Blueprint $table) {
            $table->id('id_juzgado');
            $table->unsignedBigInteger('id_sede')->nullable();
            $table->string('nom_juzgado', 50);
            $table->timestamps();

            $table->foreign('id_sede')->references('id_sede')->on('sedes')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juzgados');
    }
};
