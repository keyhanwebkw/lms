<?php

namespace App\Models;

use Keyhanweb\Subsystem\Models\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'userID',
        'userIp',
        'itemsCount',
        'currency',
        'subtotalAmount',
        'productsDiscountAmount',
        'codeDiscountAmount',
        'totalDiscountAmount',
        'shippingAmount',
        'payByBalance',
        'totalPayableAmount',
        'discountCodeID',
        'discountCode',
        'status',
        'paymentStatus',
        'paymentType',
        'productType',
        'shippingType',
        'addressID',
        'weight',
        'description',
        'adminDescription',
        'deliveryDate',
        'created',
        'updated',
    ];

    protected $casts = [
        'userID' => 'integer',
        'userIp' => 'string',
        'itemsCount' => 'integer',
        'currency' => 'string',
        'subtotalAmount' => 'integer',
        'productsDiscountAmount' => 'integer',
        'codeDiscountAmount' => 'integer',
        'totalDiscountAmount' => 'integer',
        'shippingAmount' => 'integer',
        'payByBalance' => 'integer',
        'totalPayableAmount' => 'integer',
        'discountCodeID' => 'integer',
        'discountCode' => 'string',
        'status' => 'string',
        'paymentStatus' => 'string',
        'paymentType' => 'string',
        'productType' => 'string',
        'shippingType' => 'integer',
        'addressID' => 'integer',
        'weight' => 'integer',
        'description' => 'string',
        'adminDescription' => 'string',
        'deliveryDate' => 'integer',
        'created' => 'integer',
        'updated' => 'integer',
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'orderID', 'ID');
    }
}
