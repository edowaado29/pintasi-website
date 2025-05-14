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
        Schema::create('perkembangan_motoriks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bayi')->constrained('bayis')->onDelete('cascade');
            $table->foreignId('id_motorik')->constrained('motoriks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perkembangan_motoriks');
    }
};
