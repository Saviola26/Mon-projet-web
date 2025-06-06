<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seminaires', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Référence au présentateur (lié à la table users)

            $table->string('theme'); // Sujet du séminaire
            
            $table->date('date_proposee')->nullable(); // proposée par le présentateur
            $table->date('date_validee')->nullable();  // validée par le secrétaire

            $table->text('resume')->nullable(); // Résumé envoyé par le présentateur

            $table->enum('statut', ['en_attente', 'valide', 'termine'])->default('en_attente');
            // Statut d’avancement du séminaire

            $table->integer('nombre_max_participants')->default(20); // Limite par défaut à 20
            $table->integer('nombre_participants')->default(0); // Nombre actuel de participants

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminaires');
    }
};

