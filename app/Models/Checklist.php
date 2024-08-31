<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'child_id'];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
