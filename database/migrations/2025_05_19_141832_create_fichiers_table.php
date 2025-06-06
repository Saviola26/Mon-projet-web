<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fichiers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('seminaire_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('type'); 
            $table->string('nom');  
            $table->string('chemin'); 
            $table->string('type_mime')->nullable();
            $table->unsignedBigInteger('taille')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichiers');
    }
};

