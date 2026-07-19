<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registrations extends Model
{
    protected $table = 'registrations';

    protected $fillable = ['user_id', 'event_id', 'tanggal_daftar', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Events::class, 'event_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payments::class, 'registration_id');
    }
}
