<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;

class CourseSection extends Model
{
    protected $table = 'courseSections';
    protected $fillable = [
        'courseID',
        'examRelationID',
        'title',
        'sortOrder',
        'created',
        'updated',
        'archived',
    ];
    protected $casts = [
        'ID' => 'integer',
        'courseID' => 'integer',
        'examRelationID' => 'integer',
        'title' => 'string',
        'sortOrder' => 'integer',
        'created' => 'integer',
        'updated' => 'integer',
        'archived' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'courseID');
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(SectionEpisode::class, 'courseSectionID');
    }

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
        return 'courseSections' . $key;
    }

    public static function keyCache($key = '')
    {
        return 'courseSections' . $key;
    }
}
