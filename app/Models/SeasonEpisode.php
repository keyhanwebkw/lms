<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Storage;
use Keyhanweb\Subsystem\Models\Traits\HasArchive;
use Keyhanweb\Subsystem\Models\Traits\HasDelete;

class SeasonEpisode extends Model
{
    use HasDelete;

    protected $table = 'cg_seasonEpisodes';

    protected $fillable = [
        'seasonID',
        'movieID',
        'title',
        'videoSID',
        'videoUrl',
        'sortOrder',
        'archived',
    ];

    protected $casts = [
        'ID' => 'integer',
        'seasonID' => 'integer',
        'movieID' => 'integer',
        'title' => 'string',
        'videoSID' => 'string',
        'videoUrl' => 'string',
        'sortOrder' => 'integer',
        'created' => 'integer',
        'updated' => 'integer',
        'archived' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
        static::deleted(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        MovieCategory::clearCache();
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'videoSID', 'SID');
    }
}
