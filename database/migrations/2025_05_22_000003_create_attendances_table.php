<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nfc_card_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['present', 'late', 'excused'])->default('present');
            $table->timestamp('scanned_at')->useCurrent();
            $table->string('scanner_device')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('scanned_at');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
