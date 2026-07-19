<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payments extends Model
{
    protected $table = 'payments';

    protected $fillable = ['registration_id', 'tanggal_bayar', 'jumlah', 'bukti', 'status'];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registrations::class, 'registration_id');
    }
}
