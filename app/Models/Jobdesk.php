<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobdesk extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'activity_date',
        'start_time',
        'end_time',
        'activity_type',
        'description',
        'course_id',
        'production_id',
        'training_id',
        'internal_activity_id',
        'status',
        'updated_by' // ← added
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function internalActivity()
    {
        return $this->belongsTo(InternalActivity::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
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