<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Le destinataire de la notification

            $table->foreignId('seminaire_id')->nullable()->constrained('seminaires')->onDelete('cascade');
            // Séminaire concerné, s'il y en a un

            $table->string('type'); // Exemple : 'validation', 'rappel', 'publication'
            $table->json('data')->nullable(); // Message personnalisé (optionnel)

            $table->timestamp('read_at')->nullable(); // Statut lu/non lu
            $table->timestamp('envoyee_le')->nullable(); // Date d’envoi

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

