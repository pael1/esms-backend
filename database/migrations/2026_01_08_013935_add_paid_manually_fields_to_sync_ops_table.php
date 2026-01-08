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
            $table->unsignedBigInteger('paid_manually_by')
                ->nullable()
                ->after('is_processed'); // adjust column position if needed

            $table->timestamp('paid_manually_at')
                ->nullable()
                ->after('paid_manually_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sync_ops', function (Blueprint $table) {
            $table->dropColumn([
                'paid_manually_by',
                'paid_manually_at',
            ]);
        });
    }
};
