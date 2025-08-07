<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Storage;

class Question extends Model
{
    protected $table = 'questions';
    protected $fillable = [
        'question',
        'contentSID',
        'questionDifficultyLevel',
        'timeLimit',
        'score',
        'sortOrder',
        'managerID',
        'archived',
    ];
    protected $casts = [
        'ID' => 'integer',
        'question' => 'string',
        'contentSID' => 'string',
        'questionDifficultyLevel' => 'string',
        'timeLimit' => 'integer',
        'score' => 'integer',
        'sortOrder' => 'integer',
        'managerID' => 'integer',
        'created' => 'integer',
        'updated' => 'integer',
        'archived' => 'integer',
    ];

    public static function keyCache($key = '')
    {
        return 'questions' . $key;
    }

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        Cache::tags(self::cacheTag())->flush();
        Exam::clearCache();
        ExamQuestion::clearCache();
    }

    public static function cacheTag($key = '')
    {
        return 'questions' . $key;
    }

    public function examQuestion()
    {
        return $this->hasOne(ExamQuestion::class, 'questionID');
    }

    public function options(): HasMany
    {
        return $this->hasMany(Answer::class, 'questionID');
    }

    public function correctAnswer()
    {
        return $this->hasMany(Answer::class, 'questionID')
            ->where('correct', true);
    }

    public function getCorrectAnswerIDAttribute(): ?int
    {
        return $this->correctAnswer->first()?->ID;
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'contentSID');
    }
}
