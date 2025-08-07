<?php

namespace App\Models;

use Keyhanweb\Subsystem\Models\Model;

class SupportMessage extends Model
{

    protected $table = 'supportMessages';

    public $timestamps = false;

    protected $casts = [
        'date' => 'date',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'repliedMessageID',
        'ticketID',
        'userID',
        'responderUserID',
        'message',
        'SID',
    ];

}
