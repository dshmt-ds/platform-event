<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Events extends Model
{
    protected $fillable = [
        'category_id', 
        'instructor_id', 
        'judul', 
        'deskripsi', 
        'tanggal', 
        'jam', 
        'lokasi',
        'kuota',
        'harga',
        'poster',
        'status'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructors::class, 'instructor_id');
    }
}
