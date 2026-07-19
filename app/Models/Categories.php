<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categories extends Model
{
    protected $fillable = ['nama_kategori'];

    // Kategori memiliki banyak Event
    public function events(): HasMany
    {
        return $this->hasMany(Events::class, 'category_id', 'id');
    }
}
