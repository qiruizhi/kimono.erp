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
        Schema::create('bom_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')
                ->nullable()
                ->constrained('boms')
                ->cascadeOnDelete();
            $table->foreignId('component_id')
                ->nullable()
                ->constrained('components')
                ->cascadeOnDelete();
            $table->string('type')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('unit_cost')->nullable();
            $table->decimal('compo_amount')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_components');
    }
};
