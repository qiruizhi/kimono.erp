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
        Schema::create('component_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('component_id')->nullable()->constrained('components')->cascadeOnDelete();
            $table->decimal('price')->nullable();
            $table->string('currency')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('component_prices');
    }
};
