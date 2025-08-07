<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Storage;

class SectionEpisode extends Model
{
    protected $table = 'sectionEpisodes';

    public const TYPES = [
        'content',
        'assignment',
        'exam'
    ];

    protected $fillable = [
        'courseSectionID',
        'sortOrder',
        'status',
        'isMandatory',
        'examID',
        'assignmentID',
        'episodeContentID',
        'archived',
    ];
    protected $casts = [
        'ID' => 'integer',
        'courseSectionID' => 'integer',
        'sortOrder' => 'integer',
        'status' => 'string',
        'isMandatory' => 'boolean',
        'examID' => 'integer',
        'assignmentID' => 'integer',
        'episodeContentID' => 'integer',
        'created' => 'integer',
        'updated' => 'integer',
        'archived' => 'integer',
    ];

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'contentSID');
    }

    public function courseSection(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'courseSectionID');
    }

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        Cache::tags(self::cacheTag())->flush();
        CourseSection::clearCache();

    }

    public static function cacheTag($key='')
    {
        return 'sectionEpisodes' . $key;
    }

    public static function keyCache($key = '')
    {
        return 'sectionEpisodes' . $key;
    }
}
