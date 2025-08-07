<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderSummaryResource extends JsonResource
{
    /**
     * @link https://docs.google.com/document/d/1aL5MZtVl1nJeUGHS6FeezK54YOi6-5fLh_tJZYs84ik/edit?usp=sharing
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'status' => $this->status,
            'paymentStatus' => $this->paymentStatus,
            'totalPayableAmount' => $this->totalPayableAmount,
            'itemsCount' => $this->itemsCount,
            'currency' => $this->currency,
            'deliveryDate' => $this->deliveryDate,
            'subtotalAmount' => $this->subtotalAmount,
            'productsDiscountAmount' => $this->productsDiscountAmount,
            'productType' => $this->productType,
            'created' => $this->created,
            'updated' => $this->updated,
        ];
    }
}
