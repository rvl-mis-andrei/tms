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
        Schema::create('tms_clusterb_cycle_times', function (Blueprint $table) {
            $table->id();
            $table->integer('garage_id')->nullable();
            $table->integer('client_dealership_id')->nullable();
            $table->integer('svc_garage_to_pickup')->nullable();
            $table->integer('bvc_garage_to_pickup')->nullable();
            $table->integer('time_loading')->nullable();
            $table->integer('departure_to_pickup')->nullable();
            $table->integer('dealer_to_garage')->nullable();
            $table->integer('svc_total_cycle_time')->nullable();
            $table->integer('bvc_total_cycle_time')->nullable();
            $table->integer('additional_day')->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->tinyInteger('is_deleted')->nullable();
            $table->tinyInteger('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tms_clusterb_cycle_times');
    }
};
