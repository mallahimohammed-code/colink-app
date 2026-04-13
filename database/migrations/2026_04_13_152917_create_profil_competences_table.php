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
        Schema::create('profil_competences', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('niveau', ['debutant', 'intermediaire', 'expert']);
            $table->foreignId('profil_id')->constrained('profils')->onDelete('cascade');
            $table->foreignId('competence_id')->constrained('competences')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_competences');
    }
};
