<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instructors extends Model
{
    protected $table = 'instructors';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'keahlian',
        'foto'
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Events::class, 'instructor_id');
    }
}