<?php

namespace App\Models;

use App\Enums\NotificationTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Traits\Pagination;

class  Notification extends Model
{
    use Pagination;
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'userID',
        'title',
        'content',
        'type',
        'created',
        'read',
    ];

    protected $casts = [
        'ID' => 'integer',
        'userID' => 'integer',
        'title' => 'string',
        'content' => 'string',
        'type' => 'string',
        'created' => 'integer',
        'read' => 'integer',
    ];

    public static function keyCache($key = '')
    {
        return 'notifications' . $key;
    }

    /**
     * This method creates a record in notification table which will accessible by api
     *
     * @param int $userID // who will receive the notification
     * @param string $title // title of notification
     * @param string|null $content // content (nullable)
     * @param string $type // selected from NotificationTypes
     * @return mixed
     */
    public static function send(
        int $userID,
        string $title,
        string $content = null,
        string $type = NotificationTypes::Info->value
    ) {
        $notification = self::create([
            'userID' => $userID,
            'title' => $title,
            'content' => $content,
            'type' => $type,
            'created' => time(),
        ]);

        self::clearCache($userID);

        return $notification;
    }

    protected static function clearCache($userID): void
    {
        Cache::tags(self::cacheTag($userID))->flush();
    }

    public static function cacheTag($key = '')
    {
        return 'notifications_' . $key;
    }

    /**
     * This method, mark notification which their ID received at parameters as read
     *
     * @param $IDs
     * @return void
     */
    public static function markAsReadByIDs($IDs)
    {
        $userID = Auth::user()->ID;

        $notifications = self::query()
            ->where('userID', $userID)
            ->whereNull('read')
            ->whereIn('ID', (array)$IDs)
            ->get();

        $time = time();
        foreach ($notifications as $notification) {
            $notification->read = $time;
            $notification->save();
        }

        self::clearCache($userID);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
