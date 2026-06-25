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
        Schema::create('quarterly_statistics', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('quarter');
            $table->string('airport_name');
            $table->integer('domestic_scheduled')->default(0);
            $table->integer('domestic_non_scheduled')->default(0);
            $table->integer('domestic_total')->default(0);
            $table->integer('international_scheduled')->default(0);
            $table->integer('international_non_scheduled')->default(0);
            $table->integer('international_total')->default(0);
            $table->integer('total_scheduled')->default(0);
            $table->integer('total_non_scheduled')->default(0);
            $table->integer('grand_total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quarterly_statistics');
    }
};
