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
        Schema::create('bom_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')
                ->nullable()
                ->constrained('boms')
                ->cascadeOnDelete();
            $table->string('step')->nullable();
            $table->string('operation')->nullable();
            $table->string('workstation')->nullable();
            $table->float('op_time')->nullable();
            $table->string('unit_time')->nullable();
            $table->boolean('fixed_time')->default(false);
            $table->decimal('op_unit_cost')->nullable();
            $table->decimal('op_amount')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_operations');
    }
};
