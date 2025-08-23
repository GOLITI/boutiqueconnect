<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // L'e-mail de l'utilisateur, comme clé primaire
            $table->string('token');            // Le jeton de réinitialisation unique
            $table->timestamp('created_at')->nullable(); // Horodatage de création du jeton
        });
    }

    /**
     * Annule les migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
