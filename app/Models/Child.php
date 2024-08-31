<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function professionals()
    {
        return $this->belongsToMany(User::class, 'child_professional', 'child_id', 'professional_id');
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }
}
