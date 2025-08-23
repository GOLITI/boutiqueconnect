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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // ID de la notification
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // L'utilisateur à qui la notification est destinée
            $table->string('type'); // Type de notification (ex: 'stock_alert', 'new_sale', 'credit_overdue')
            $table->text('message'); // Contenu du message de la notification
            $table->boolean('is_read')->default(false); // Statut de lecture (true si lue, false sinon)
            $table->json('data')->nullable(); // Données supplémentaires au format JSON (ex: product_id, sale_id)
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
