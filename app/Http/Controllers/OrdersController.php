<?php

namespace App\Http\Controllers;

use App\DTO\Sort\SortDTOCollection;
use App\Enums\Order\DeliveryPlaceEnum;
use App\Enums\Order\OrderStatusEnum;
use App\Enums\Order\PaymentTypeEnum;
use App\Enums\SortEnum;
use App\Filters\OrderFilter;
use App\Http\Requests\Order\OrderGetRequest;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Models\Cart\Cart;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Services\StringsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class OrdersController extends Controller
{
    protected ?Collection $cartItems = null;

    public function isOneclick(Request|FormRequest $request)
    {
        $isOneclick = $request->get('is_oneclick');
        if ($isOneclick === 'false' || !$isOneclick)
            $isOneclick = false;
        else
            $isOneclick = true;

        return $isOneclick;
    }

    public function getCartItems(bool $isOneclick)
    {
        if (!$this->cartItems) {
            $this->cartItems = Cart::where('is_oneclick', $isOneclick)
                ->where('user_id', auth()->user()->id)
                ->with(
                    'variation:id,product_id,slug,name,price,discount,quantity,image_id',
                    'variation.product:id,name,slug,image_id'
                )
                ->get();
        }
        return $this->cartItems;
    }

    /**
     * Валидация заказа из корзины. Если некоторых товаров нет/не хватает в наличии, выдать сообщение об этом
     */
    public function creationAttempt(OrderStoreRequest $request)
    {
        /**
         * Если всего товара хватает - $message не будет сформирован и останется пустым
         */
        $message = '';
        $this->getCartItems($this->isOneclick($request))
            ->each(function (Cart $item) use (&$message) {
                $missing = $item->quantity - $item->variation->quantity;
                if ($missing > 0) {
                    $addToMsg = __('validation.order.notEnoughItems.missing', [
                        'productName' => $item->variation->product->name.' ('.$item->variation->name.')',
                        'quantity' => $missing
                    ]);
                    if (strlen($message) < 1) {
                        $message .= __('validation.order.notEnoughItems.attention').$addToMsg;
                    } else {
                        $message .= '; <br />'.$addToMsg;
                    }
                }
            });

        /** Если весь товар в наличии - передать запрос в $this->create */
        if (strlen($message) < 1) {
            $response = $this->create($request);
        } else {
            $response = response([
                'ok' => false,
                'reason' => 'failed_quantity',
                'message' => $message,
            ], 422);
        }

        return $response;
    }

    /** Создание заказа: если товара нет в наличии в указанном количестве - удалит лишнее количество */
    public function create(OrderStoreRequest $request)
    {
        $cartItems = $this->getCartItems($this->isOneclick($request))
            ->filter(
                fn(Cart $cartItem)
                => $cartItem->variation->quantity > 0
            );
        $noCart = $cartItems->count() < 1;
        throw_if(
            $noCart,
            new UnprocessableEntityHttpException(__('validation.order.noCart'))
        );

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'orderer_data' => [
                'name' => $request->validated('orderer_name'),
                'email' => $request->validated('email'),
                'telegram' => $request->validated('telegram'),
                'phone_number' => $request->validated('phone_number'),
            ],
            'delivery_place' => $request->validated('delivery_place'),
            'delivery_address' => $request->validated('delivery_address'),
            'order_status' => OrderStatusEnum::PREPARING,
            'desired_payment_type' => $request->validated('desired_payment_type'),
            'is_paid' => true,
        ]);

        $cartItems->each(function (Cart $item) use ($order) {
            $price = $item->variation->getCurrentPrice();
            $quantity = $item->quantity;
            $missing = $quantity - $item->variation->quantity;
            if ($missing > 0) {
                $quantity -= $missing;
            }
            if ($quantity < 1)
                return;

            $item->variation->update([
                'quantity' => $item->variation->quantity - $quantity
            ]);

            OrderProduct::create([
                'order_id' => $order->id,
                'product_variation_id' => $item->variation->id,
                'product_name' => $item->variation->product->name.' ('.$item->variation->name.')',
                'product_quantity' => $quantity,
                'product_price' => $price,
                'product_total_cost' => $price * $quantity,
            ]);
        });

        Cart::whereIn('id', $cartItems->map(fn(Cart $item) => $item->id))
            ->delete();

        $order->createCollage(
            $cartItems
                ->map(fn($cartItem) => $cartItem->variation)
        );

        return response([
            'ok' => true,
            'message' => __('general.orderCreated', ['num' => $order->id])
        ], 201);
    }

    public function cancel(OrderGetRequest $request)
    {
        $request->order->update([
            'order_status' => OrderStatusEnum::CANCELLED
        ]);

        return response([
            'ok' => true,
            'message' => __('general.orderCancelled', ['num' => $request->order->id])
        ]);
    }

    public function getOrdersList(OrderFilter $request)
    {
        $sortData = SortDTOCollection::getSortsFromRequest(SortEnum::ORDERS);

        return response(
            Order::forList()
                ->totalCost()
                ->where('user_id', auth()->user()->id)
                ->with([
                    'image:id,name,extension,sort,path,alt,disk',
                    'products:id,order_id,product_name,product_quantity,product_price,product_total_cost'
                ])
                ->filter($request)
                ->orderBy($sortData['sort'], $sortData['sortOrder'])
                ->paginate(request('per_page') ?? 8)
        );
    }

    public function getOrder(OrderGetRequest $request)
    {
        $request->order->load([
            'image:id,name,extension,sort,path,alt,disk',
            'products:id,order_id,product_name,product_quantity,product_price,product_total_cost'
        ]);

        return response([
            'ok' => true,
            'data' => [
                'order' => $request->order
            ]
        ]);
    }

    /**
     * - Списки: способы доставки, способы оплаты
     * - Информация о готовящемся заказе: сумма, стоимость доставки, итог...
     * - Корзина: текущая корзина для выбранного типа is_oneclick
     */
    public function getOrderCreationData(Request $request)
    {
        $cartItems = $this->getCartItems($this->isOneclick($request));

        throw_if(
            $cartItems->count() < 1,
            new UnprocessableEntityHttpException(
                __('validation.order.noCart')
            )
        );

        $orderCost = round($cartItems
            ->reduce(
                fn(int $current, Cart $next) =>
                $current + $next->variation->getCurrentPrice(),
                0
            ), 2);
        $deliveryCost = 0;

        return response([
            'ok' => true,
            'data' => [
                'variants' => [
                    'delivery_places' => StringsService::enumToStringsArray(DeliveryPlaceEnum::cases()),
                    'payment_types' => StringsService::enumToStringsArray(PaymentTypeEnum::cases())
                ],
                'summary' => [
                    'order_cost' => $orderCost,
                    'delivery_cost' => $deliveryCost,
                    'total_cost' => $orderCost + $deliveryCost
                ],
                'cart' => Cart::currentUser($this->isOneclick($request))->get()
            ]
        ]);
    }
}
