<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attempt extends Model
{
    protected $fillable = [
        'quiz_id',
        'participant_name',
        'participant_email',
        'total_marks',
        'obtained_marks',
        'is_completed',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Calculate percentage score
     */
    public function getPercentage(): float
    {
        if ($this->total_marks == 0) {
            return 0;
        }
        return round(($this->obtained_marks / $this->total_marks) * 100, 2);
    }

    /**
     * Get attempt duration in minutes
     */
    public function getDurationMinutes(): int
    {
        if (!$this->started_at || !$this->completed_at) {
            return 0;
        }
        return $this->completed_at->diffInMinutes($this->started_at);
    }
}
