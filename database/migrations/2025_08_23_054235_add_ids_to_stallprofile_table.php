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
        Schema::table('stallprofile', function (Blueprint $table) {
            $table->string('section_id')->nullable();
            $table->string('sub_section_id')->nullable();
            $table->string('building_id')->nullable();
            $table->string('stall_id_ext')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stallprofile', function (Blueprint $table) {
            //
        });
    }
};
