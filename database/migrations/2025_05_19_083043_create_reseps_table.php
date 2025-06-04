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
        Schema::create('reseps', function (Blueprint $table) {
            $table->id();
            $table->string('nama_resep');
            $table->text('langkah');
            $table->integer('min_usia');
            $table->integer('max_usia');
            $table->double('total_kalori');
            $table->double('total_protein');
            $table->double('total_lemak');
            $table->double('total_karbohidrat');
            $table->double('total_serat');
            $table->string('gambar_resep')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};
