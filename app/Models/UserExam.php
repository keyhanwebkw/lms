<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;

class UserExam extends Model
{
    protected $table = 'userExams';

    protected $fillable = [
        'examID',
        'userID',
        'examStatus',
        'retryCount',
        'score',
        'trueAnswers',
        'falseAnswers',
        'skippedAnswers',
    ];

    protected $casts = [
        'ID' => 'integer',
        'examID' => 'integer',
        'userID' => 'integer',
        'examStatus' => 'string',
        'retryCount' => 'integer',
        'score' => 'integer',
        'trueAnswers' => 'integer',
        'falseAnswers' => 'integer',
        'skippedAnswers' => 'integer',
        'created' => 'integer',
        'updated' => 'integer',
    ];

    public static function keyCache($key = '')
    {
        return 'userExams' . $key;
    }

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        Cache::tags(self::cacheTag())->flush();
    }

    public static function cacheTag($key = '')
    {
        return 'userExams' . $key;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
