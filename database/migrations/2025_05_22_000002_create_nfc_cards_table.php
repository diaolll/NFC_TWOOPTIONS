<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nfc_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('serial_number')->unique();
            $table->text('data')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamps();

            $table->index('serial_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nfc_cards');
    }
};
