<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Storage;
use Keyhanweb\Subsystem\Models\Traits\HasArchive;

class Assignment extends Model
{

    protected $fillable = [
        'title',
        'description',
        'contentSID',
        'deadline',
        'score',
        'minScoreToPass',
        'retryCount',
        'archived',
    ];

    protected $casts = [
        'ID' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'contentSID' => 'string',
        'deadline' => 'integer',
        'score' => 'integer',
        'minScoreToPass' => 'integer',
        'retryCount' => 'integer',
        'created' => 'integer',
        'updated' => 'integer',
        'archived' => 'integer',
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
        return 'assignments' . $key;
    }

    public static function keyCache($key = '')
    {
        return 'assignments' . $key;
    }

    public function storage()
    {
        return $this->hasOne(Storage::class, 'SID', 'contentSID');
    }
}
