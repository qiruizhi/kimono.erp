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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('category')->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('cost')->nullable();
            $table->string('currency')->nullable();
            $table->string('status')->default('active');
            $table->string('lead_time')->nullable();
            $table->longText('notes')->nullable();
            $table->decimal('weight_value', 10, 3)->nullable()
                ->default(0.00)
                ->unsigned();
            $table->string('weight_unit')->default('gm')->nullable();
            $table->decimal('height_value', 10, 3)->nullable()
                ->default(0.00)
                ->unsigned();
            $table->string('height_unit')->default('mm')->nullable();
            $table->decimal('depth_value', 10, 3)->nullable()
                ->default(0.00)
                ->unsigned();
            $table->string('depth_unit')->default('mm')->nullable();
            $table->decimal('width_value', 10, 3)->nullable()
                ->default(0.00)
                ->unsigned();
            $table->string('width_unit')->default('mm')->nullable();
            $table->decimal('volume_value', 10, 3)->nullable()
                ->default(0.00)
                ->unsigned();
            $table->string('volume_unit')->default('ml')->nullable();
            $table->string('primary_material')->nullable();
            $table->string('secondary_material')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
