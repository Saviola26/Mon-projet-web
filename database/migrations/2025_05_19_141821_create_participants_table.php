<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('participations', function (Blueprint $table) {
            $table->id();
            
            // Clé étrangère vers la table users
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
                  
            // Clé étrangère vers la table seminaires
            $table->foreignId('seminaire_id')
                  ->constrained()
                  ->onDelete('cascade');
                 
            // Date d'inscription
            $table->timestamp('date_inscription')
                  ->useCurrent();
                  
            // Empêche les doublons user_id + seminaire_id
            $table->unique(['user_id', 'seminaire_id']);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('participations');
    }
};