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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('server_id')->unique();
            $table->string('status')->default('creating'); // creating, active, suspended, terminated
            $table->string('minecraft_version')->default('1.20.1');
            $table->string('server_type')->default('vanilla'); // vanilla, spigot, paper, forge, fabric
            $table->string('ip_address')->nullable();
            $table->integer('port')->nullable();
            $table->integer('current_players')->default(0);
            $table->integer('max_players')->default(20);
            $table->integer('memory_usage')->default(0); // MB
            $table->integer('max_memory')->default(1024); // MB
            $table->string('uptime')->default('0h 0m');
            $table->decimal('monthly_cost', 8, 2)->default(0.00);
            $table->timestamp('last_online')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
