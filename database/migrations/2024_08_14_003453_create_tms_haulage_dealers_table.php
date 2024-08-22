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
        Schema::create('tms_haulage_dealers', function (Blueprint $table) {
            $table->id();
            $table->integer('haulage_id')->nullable();
            $table->integer('dealer_id');
            $table->string('no_of_trips')->nullable();
            $table->integer('tractor_id')->nullable();
            $table->integer('trailer_id')->nullable();
            $table->tinyInteger('is_deleted')->nullable();
            $table->tinyInteger('deleted_by')->nullable();
            $table->tinyInteger('deleted_at')->nullable();
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
        Schema::dropIfExists('tms_haulage_dealers');
    }
};
