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
        Schema::create('daftar_bahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->double('kalori');
            $table->double('protein');
            $table->double('lemak');
            $table->double('karbohidrat');
            $table->double('serat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_bahans');
    }
};
