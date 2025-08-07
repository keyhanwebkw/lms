<?php

namespace App\Models;

use App\Enums\NotificationTypes;
use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Keyhanweb\Subsystem\Models\Storage;
use Keyhanweb\Subsystem\Models\User as UserSubsystemModel;

class User extends UserSubSystemModel
{

    protected $fillable = [
        'name',
        'family',
        'countryCode',
        'mobile',
        'companyName',
        'gender',
        'balance',
        'nationalCode',
        'phone',
        'birthDate',
        'avatarSID',
        'status',
        'refereeUserID',
        'referralCode',
        'referredUsersCount',
        'score',
        'registerDate',
        'lastActivity',
        'isDelete',
        'twoFAPassword',
        'email',
        'password',
        'type',
        'parentID',
        'username',
    ];
    protected $casts = [
        'ID' => 'integer',
        'name' => 'string',
        'family' => 'string',
        'countryCode' => 'integer',
        'mobile' => 'string',
        'companyName' => 'string',
        'gender' => 'string',
        'balance' => 'integer',
        'nationalCode' => 'string',
        'phone' => 'string',
        'birthDate' => 'integer',
        'avatarSID' => 'string',
        'status' => 'string',
        'refereeUserID' => 'integer',
        'referralCode' => 'string',
        'referredUsersCount' => 'integer',
        'score' => 'integer',
        'registerDate' => 'integer',
        'lastActivity' => 'integer',
        'deleted' => 'integer',
        'twoFAPassword' => 'string',
        'email' => 'string',
        'password' => 'string',
        'type' => 'string',
        'parentID' => 'integer',
        'username' => 'string',
    ];

    public function getAvatarAttribute()
    {
        return Storage::findBySID($this->avatarSID);
    }

    /**
     * @return BelongsTo|string
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parentID')->withDefault();
    }

    /**
     * @return HasMany|string
     */
    public function children()
    {
        return $this->hasMany(User::class, 'parentID');
    }

    /**
     * @return string
     */
    public static function generateUsername()
    {
        $username = 'user' . '_' . date('ymd') . rand(1, 999);
        $counter = 1;
        $originalUsername = $username;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }
        return $username;
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->family;
    }

    public static function storageCheck($storage, $authUser)
    {
        return true;
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function storage()
    {
        return $this->hasOne(Storage::class,'SID','avatarSID');
    }
}
