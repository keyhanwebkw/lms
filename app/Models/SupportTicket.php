<?php

namespace App\Models;

use Keyhanweb\Subsystem\Models\Model;

class SupportTicket extends Model
{
    protected $table = 'supportTickets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userID',
        'departmentID',
        'title',
        'userID',
        'userLastMessageID',
        'adminLastMessageID',
        'lastResponderUserID',
        'status',
        'updated',
    ];

    const STATUS = [
        'none',
        'open',
        'closed',
        'answered',
        'inProgress',
        'waitingForCustomer',
        'lock',
    ];

    /**
     * customer relation
     */
    public function user()
    {
        return $this->hasOne(User::class, 'ID', 'userID');
    }
}
