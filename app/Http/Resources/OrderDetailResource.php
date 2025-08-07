<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * @link https://docs.google.com/document/d/1aL5MZtVl1nJeUGHS6FeezK54YOi6-5fLh_tJZYs84ik/edit?usp=sharing
     *
     * Transform the resource into a summary array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->ID,
            'orderID' => $this->orderID,
            'productID' => $this->productID,
            'courseID' => $this->courseID,
            'course' => CourseOrderSummaryResource::make($this->course),
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
            'unitPriceWithDiscount' => $this->unitPriceWithDiscount,
            'totalAmount' => $this->totalAmount,
            'totalAmountWithDiscount' => $this->totalAmountWithDiscount,
            'discountAmount' => $this->discountAmount,
            'type' => $this->type,
            'created' => $this->created,
            'updated' => $this->updated,
        ];
    }
}
