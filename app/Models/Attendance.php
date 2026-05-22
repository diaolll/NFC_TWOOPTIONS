<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'nfc_card_id', 'status', 'scanned_at', 'scanner_device', 'notes'])]
class Attendance extends Model
{
    protected function casts(): array
    {
        return [
            'scanned_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function nfcCard(): BelongsTo
    {
        return $this->belongsTo(NfcCard::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scanned_at', today());
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }
}
