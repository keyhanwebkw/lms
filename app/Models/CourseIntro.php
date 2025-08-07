<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Storage;

class CourseIntro extends Model
{
    use hasFactory;

    protected $table = 'courseIntros';
    public $timestamps = false;

    protected $fillable = [
        'courseID',
        'type',
        'SID',
        'url',
    ];
    protected $casts = [
        'ID' => 'integer',
        'courseID' => 'integer',
        'type' => 'string',
        'SID' => 'string',
        'url' => 'string',
    ];

    protected static function booted(): void
    {
        static::saved(fn() => self::clearCache());
        static::deleted(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        Cache::forget(Course::keyCache('*'));
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'SID');
    }
}
