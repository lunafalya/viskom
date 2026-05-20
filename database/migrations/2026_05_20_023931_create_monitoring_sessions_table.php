<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->integer('microsleep_count')->default(0);
            $table->integer('perclos_alerts')->default(0);
            $table->integer('yawn_count')->default(0);
            $table->string('status')->default('active'); // active, completed, interrupted
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_sessions');
    }
};
