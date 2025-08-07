<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Storage;

class EpisodeContent extends Model
{
    protected $table = 'episodeContents';

    protected $fillable = [
        'title',
        'duration',
        'description',
        'contentSID',
    ];

    protected $casts = [
        'ID' => 'integer',
        'title' => 'string',
        'duration' => 'string',
        'description' => 'string',
        'contentSID' => 'string',
        'created' => 'integer',
        'updated' => 'integer',
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
        return 'episodeContents' . $key;
    }

    public static function keyCache($key = '')
    {
        return 'episodeContents' . $key;
    }

    public function storage()
    {
        return $this->hasOne(Storage::class, 'SID', 'contentSID');
    }
}
