<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;

class Exam extends Model
{
    public $timestamp = false;

    protected $fillable = [
        'title',
        'description',
        'startDate',
        'endDate',
        'score',
        'duration',
        'minScoreToPass',
        'retryAttempts',
        'managerID',
    ];
    protected $casts = [
        'ID' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'startDate' => 'integer',
        'endDate' => 'integer',
        'score' => 'integer',
        'duration' => 'integer',
        'minScoreToPass' => 'integer',
        'retryAttempts' => 'integer',
        'managerID' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        Cache::tags(self::cacheTag())->flush();
    }

    public static function cacheTag($key='')
    {
        return 'exams' . $key;
    }

    public static function keyCache($key = '')
    {
        return 'exams' . $key;
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class, 'examID');
    }
}
