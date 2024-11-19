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
        Schema::table('trips', function (Blueprint $table) {
            $table->timestamp('assigned_date')->nullable();
            $table->timestamp('confirmed_date')->nullable();
            $table->string('cancelled_from_state')->nullable();
            $table->timestamp('cancelled_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('assigned_date');
            $table->dropColumn('confirmed_date');
            $table->dropColumn('cancelled_from_state');
            $table->dropColumn('cancelled_date');
        });
    }
};
