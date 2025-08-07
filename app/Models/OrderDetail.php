<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Keyhanweb\Subsystem\Models\Model;

class OrderDetail extends Model
{
    protected $table = 'orderDetails';

    protected $fillable = [
        'orderID',
        'productID',
        'courseID',
        'quantity',
        'unitPrice',
        'unitPriceWithDiscount',
        'totalAmount',
        'totalAmountWithDiscount',
        'discountAmount',
        'type',
        'created',
        'updated',
    ];

    protected $casts = [
        'ID' => 'integer',
        'orderID' => 'integer',
        'productID' => 'integer',
        'courseID' => 'integer',
        'quantity' => 'integer',
        'unitPrice' => 'integer',
        'unitPriceWithDiscount' => 'integer',
        'totalAmount' => 'integer',
        'totalAmountWithDiscount' => 'integer',
        'discountAmount' => 'integer',
        'type' => 'string',
        'created' => 'integer',
        'updated' => 'integer',
    ];
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'courseID');
    }
}
