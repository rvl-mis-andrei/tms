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
        Schema::create('tms_clusterb_plannings', function (Blueprint $table) {
            $table->id();
            $table->integer('cluster_id');
            $table->integer('haulage_id')->nullable();
            $table->integer('dealer_id');
            $table->date('invoice_date')->nullable();
            $table->string('invoice_time')->nullable();
            $table->string('planning_cutoff')->nullable();
            $table->string('vld_instruction')->nullable();
            $table->string('updated_location')->nullable();
            $table->string('vdn_number')->nullable();
            $table->string('vld_planner_confirmation')->nullable();
            $table->string('hub')->nullable();
            $table->string('remarks')->nullable();
            $table->string('assigned_lsp')->nullable();
            $table->integer('car_model_id')->nullable();
            $table->integer('cs_no')->nullable();
            $table->integer('color_description')->nullable();
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
        Schema::dropIfExists('tms_clusterb_plannings');
    }
};
