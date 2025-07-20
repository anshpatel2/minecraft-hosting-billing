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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->after('plan_id');
            $table->decimal('total_price', 10, 2)->after('amount');
            $table->date('due_date')->nullable()->after('paid_at');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('due_date');
            $table->text('refund_reason')->nullable()->after('refund_amount');
            $table->timestamp('refunded_at')->nullable()->after('refund_reason');
            $table->text('notes')->nullable()->after('refunded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'quantity',
                'total_price',
                'due_date',
                'refund_amount',
                'refund_reason',
                'refunded_at',
                'notes'
            ]);
        });
    }
};
