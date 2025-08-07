<?php

namespace App\Models;

use Keyhanweb\Subsystem\Models\Model;

class ExamQuestion extends Model
{
    protected $table = 'examQuestions';
    public $timestamps = false;

    protected $fillable = [
        'examID',
        'questionID',
    ];
    protected $casts = [
        'ID' => 'integer',
        'examID' => 'integer',
        'questionID' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saved(fn() => self::clearCache());
        static::deleted(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        Exam::clearCache();
    }
}
