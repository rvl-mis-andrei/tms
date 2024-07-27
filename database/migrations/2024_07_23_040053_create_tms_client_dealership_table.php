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
        Schema::create('tms_client_dealerships', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code',10)->nullable();
            $table->integer('client_id');
            $table->integer('location_id');
            $table->tinyInteger('is_active');
            $table->longText('receiving_personnel')->nullable();
            $table->integer('pv_lead_time')->nullable();
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
        Schema::dropIfExists('tms_client_dealerships');
    }
};
