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
        Schema::create('tms_cluster_car_models', function (Blueprint $table) {
            $table->id();
            $table->integer('car_model_id');
            $table->string('car_model_color_id');
            $table->tinyInteger('is_active')->nullable();
            $table->tinyInteger('is_deleted')->nullable();
            $table->tinyInteger('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tms_cluster_car_models');
    }
};
