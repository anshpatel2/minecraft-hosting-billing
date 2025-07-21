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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('monthly_revenue', 10, 2)->default(0);
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->decimal('monthly_spend', 10, 2)->default(0);
            $table->integer('servers_count')->default(0);
            $table->unsignedBigInteger('reseller_id')->nullable();
            
            $table->foreign('reseller_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['reseller_id']);
            $table->dropColumn(['monthly_revenue', 'commission_rate', 'monthly_spend', 'servers_count', 'reseller_id']);
        });
    }
};
