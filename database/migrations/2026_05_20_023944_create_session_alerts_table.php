<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('monitoring_sessions')->cascadeOnDelete();
            $table->string('type');       // microsleep, perclos, yawn
            $table->string('severity');   // critical, warning
            $table->float('value')->nullable();
            $table->timestamp('triggered_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_alerts');
    }
};
