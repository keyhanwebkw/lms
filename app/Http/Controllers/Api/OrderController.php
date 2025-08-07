<?php

namespace App\Http\Controllers\Api;

use App\Enums\CourseIntroTypes;
use App\Enums\OrderDetailTypes;
use App\Enums\OrderProductTypes;
use App\Enums\OrderStatuses;
use App\Http\Requests\Api\Order\ItemRemoveRequest;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderSummaryResource;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Keyhanweb\Subsystem\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Order\ItemAddRequest;

class OrderController extends Controller
{

    /**
     * @link https://docs.google.com/document/d/15b-rifMWvTTz928C_dsPGI0MPUwydKLPCgotCn_bJ9Q/edit?usp=sharing
     *
     * @param ItemAddRequest $request
     * @return JsonResponse
     */
    public function itemAdd(ItemAddRequest $request)
    {
        $data = $request->validated();
        $userID = Auth::id();

        $courseID = $data['courseID'] ?? null;
        $productID = $data['productID'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        $unitPrice = 0;
        $discount = 0;

        if ($courseID) {
            $course = Course::query()
                ->select('ID', 'price', 'discountAmount')
                ->where('ID', $courseID)
                ->where('price', '!=', 0)
                ->where('startDate', '<', time())
                ->first();

            if (!$course) {
                return $this->error(1, st('record not found'));
            }

            $unitPrice = $course->price;
            $discount = $course->discountAmount;
            $quantity = 1;
        }

        // Todo: Add product existing and price process

        $order = Order::query()
            ->where('userID', $userID)
            ->whereIn('status', OrderStatuses::availableForUser())
            ->first();

        if (!$order) {
            $order = Order::create([
                'userID' => $userID,
                'status' => OrderStatuses::pending->value,
                'userIp' => $request->ip(),
            ]);
        }

        $unitPriceWithDiscount = $unitPrice - $discount ?? 0;
        $totalAmount = $unitPrice * $quantity ?? 0;
        $totalAmountWithDiscount = $unitPriceWithDiscount * $quantity ?? 0;
        $discountAmount = $discount * $quantity ?? 0;

        $orderDetail = OrderDetail::query()
            ->where('orderID', $order->ID)
            ->when($courseID, fn($q) => $q->where('courseID', $courseID))
            ->when($productID, fn($q) => $q->where('productID', $productID))
            ->first();

        if ($orderDetail) {
            $orderDetail->update([
                'quantity' => $quantity,
                'unitPrice' => $unitPrice,
                'unitPriceWithDiscount' => $unitPriceWithDiscount,
                'totalAmount' => $totalAmount,
                'totalAmountWithDiscount' => $totalAmountWithDiscount,
                'discountAmount' => $discountAmount,
            ]);
        } else {
            OrderDetail::create([
                'orderID' => $order->ID,
                'courseID' => $courseID,
                'productID' => $productID,
                'quantity' => $quantity,
                'unitPrice' => $unitPrice,
                'unitPriceWithDiscount' => $unitPriceWithDiscount,
                'totalAmount' => $totalAmount,
                'totalAmountWithDiscount' => $totalAmountWithDiscount,
                'discountAmount' => $discountAmount,
                'type' => $courseID ? OrderDetailTypes::virtual->value : OrderDetailTypes::physical->value,
            ]);
        }

        $order->load('details.course');
        $order->itemsCount = $order->details->count();
        $order->subtotalAmount = $order->details->sum('totalAmount');
        $order->productsDiscountAmount = $order->details->sum('discountAmount');
        $order->totalPayableAmount = $order->details->sum('totalAmountWithDiscount');
        $order->save();

        return $this->success([
            'order' => OrderSummaryResource::make($order),
            'orderItems' => OrderDetailResource::collection($order->details),
        ]);
    }

    /**
     * @link https://docs.google.com/document/d/1JskTNjQWWTnYu6HytTFbbO3LtknyONCU1eiKYGR618w/edit?usp=sharing
     *
     * @param ItemRemoveRequest $request
     * @return JsonResponse
     */
    public function itemRemove(ItemRemoveRequest $request)
    {
        $data = $request->validated();
        $userID = Auth::id();

        $order = Order::query()
            ->where('userID', $userID)
            ->whereIn('status', OrderStatuses::availableForUser())
            ->first();

        if (!$order) {
            return $this->error(1, st('no active cart found'));
        }

        if ($data['removeType'] === 'all') {
            $order->details()->delete();
            $order->delete();
            return $this->success();
        }

        $orderDetail = OrderDetail::query()
            ->where('orderID', $order->ID)
            ->when(!empty($data['courseID']), fn($q) => $q->where('courseID', $data['courseID']))
            ->when(!empty($data['productID']), fn($q) => $q->where('productID', $data['productID']))
            ->first();

        if (!$orderDetail) {
            return $this->error(2, st('item not found in cart'));
        }

        switch ($data['removeType']) {
            case 'one':
                if ($orderDetail->quantity > 1) {
                    $orderDetail->quantity -= 1;
                    $orderDetail->totalAmount = $orderDetail->quantity * $orderDetail->unitPrice;
                    $orderDetail->totalAmountWithDiscount = $orderDetail->quantity * $orderDetail->unitPriceWithDiscount;
                    $orderDetail->discountAmount = $orderDetail->quantity * ($orderDetail->unitPrice - $orderDetail->unitPriceWithDiscount);
                    $orderDetail->save();
                } else {
                    $orderDetail->delete();
                }
                break;

            case 'item':
                $orderDetail->delete();
                break;
        }

        if ($order->details()->count() == 0) {
            $order->delete();
        } else {
            $order->load('details.course');
            $order->itemsCount = $order->details->count();
            $order->subtotalAmount = $order->details->sum('totalAmount');
            $order->productsDiscountAmount = $order->details->sum('discountAmount');
            $order->totalPayableAmount = $order->details->sum('totalAmountWithDiscount');
            $order->save();
        }

        return $this->success([
            'order' => OrderSummaryResource::make($order),
            'orderItems' => OrderDetailResource::collection($order->details),
        ]);
    }

    public function itemList()
    {
        $userID = Auth::id();

        $order = Order::query()
            ->with([
                'details.course' => function ($query) {
                    $query->with([
                        'teacher' => function ($query) {
                            $query->select('ID', 'name', 'family', 'avatarSID')->with('storage');
                        },
                        'courseIntro' => function ($query) {
                            $query->where('type', CourseIntroTypes::Banner->value)->with('storage');
                        },
                        'categories'
                    ]);
                }
            ])
            ->where('userID', $userID)
            ->whereIn('status', OrderStatuses::availableForUser())
            ->first();

        if (!$order) {
            return $this->success();
        }

        if ($order->details->isEmpty()) {
            $order->delete();
            return $this->error(2, st('item not found in cart'));
        }

        $typeCounts = $order->details->groupBy('type')->keys();

        if ($typeCounts->contains(OrderDetailTypes::physical->value) && $typeCounts->contains(OrderDetailTypes::virtual->value)) {
            $order->productType = OrderProductTypes::both->value;
        } elseif ($typeCounts->contains(OrderDetailTypes::physical->value)) {
            $order->productType = OrderProductTypes::physical->value;
        } else {
            $order->productType = OrderProductTypes::virtual->value;
        }

        $order->save();

        return $this->success([
            'order' => OrderSummaryResource::make($order),
            'orderItems' => OrderDetailResource::collection($order->details),
        ]);
    }
}
