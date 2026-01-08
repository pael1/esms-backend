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
        Schema::table('sync_ops', function (Blueprint $table) {
            $table->text('reason')
                ->nullable()
                ->after('paid_manually_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sync_ops', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
    }
};
