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
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->decimal('monthly_spend', 10, 2)->default(0.00);
            $table->decimal('total_paid', 10, 2)->default(0.00);
            $table->decimal('outstanding_balance', 10, 2)->default(0.00);
            $table->decimal('monthly_revenue', 10, 2)->default(0.00);
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->foreignId('reseller_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'balance',
                'monthly_spend', 
                'total_paid',
                'outstanding_balance',
                'monthly_revenue',
                'commission_rate',
                'reseller_id'
            ]);
        });
    }
};
