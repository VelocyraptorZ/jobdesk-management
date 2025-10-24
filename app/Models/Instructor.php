<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'employee_id', 
        'field_of_expertise', 
        'is_active', 
        'updated_by'
    ];

    public function jobdesks()
    {
        return $this->hasMany(Jobdesk::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }
}