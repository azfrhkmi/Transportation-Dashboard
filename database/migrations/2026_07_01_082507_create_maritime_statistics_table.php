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
        Schema::create('maritime_statistics', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('quarter');
            $table->string('port_name');
            $table->integer('int_mother')->default(0);
            $table->integer('int_feeder')->default(0);
            $table->integer('int_cargo')->default(0);
            $table->integer('int_tanker')->default(0);
            $table->integer('int_bulk')->default(0);
            $table->integer('int_others')->default(0);
            $table->integer('int_total')->default(0);
            $table->integer('dom_mother')->default(0);
            $table->integer('dom_feeder')->default(0);
            $table->integer('dom_cargo')->default(0);
            $table->integer('dom_tanker')->default(0);
            $table->integer('dom_bulk')->default(0);
            $table->integer('dom_others')->default(0);
            $table->integer('dom_total')->default(0);
            $table->integer('others')->default(0);
            $table->integer('grand_total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maritime_statistics');
    }
};
