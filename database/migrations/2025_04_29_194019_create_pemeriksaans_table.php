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
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bayi_id')->constrained('bayis')->onDelete('cascade');
            $table->date('tanggal_pemeriksaan');
            $table->float('berat_badan');
            $table->float('tinggi_badan');
            $table->float('lingkar_kepala');
            $table->text('catatan');
            $table->string('status_gizi');
            $table->integer('kalori');
            $table->integer('protein');
            $table->integer('lemak');
            $table->integer('karbohidrat');
            $table->integer('serat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};
