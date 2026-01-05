<?php

declare(strict_types=1);

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
        Schema::create('marketing_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_service_id')->constrained('marketing_services')->onDelete('cascade');
            $table->string('key');
            $table->text('value');
            $table->timestamps();

            // Composite unique key
            $table->unique(['marketing_service_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_credentials');
    }
};
