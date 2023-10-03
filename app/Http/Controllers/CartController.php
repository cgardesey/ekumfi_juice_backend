<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartProduct;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\InstructorCart;
use App\Payment;
use App\SubscriptionChangeRequest;
use App\Traits\PaymentsTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class CartController extends Controller
{
    use PaymentsTrait;

    public function scopedConsumerCarts(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $this->updatedLatestConsumerPaymentStatus($user);

        return DB::table('carts')
            ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
            ->join('sellers', 'carts.seller_id', '=', 'sellers.seller_id')
            ->join('consumers', 'carts.consumer_id', '=', 'consumers.consumer_id')
            ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
            ->select('carts.*', 'payments.status AS status', 'consumers.longitude AS consumer_longitude', 'consumers.latitude AS consumer_latitude', 'consumers.name AS consumer_name', 'consumers.profile_image_url AS consumer_profile_image_url', 'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name', 'sellers.verified', 'sellers.shop_image_url', 'sellers.availability', DB::raw("count(cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.consumer_id = ?', [request('consumer_id')])
            ->groupBy('carts.id')
            ->get();
    }

    public function scopedSellerCarts(Request $request)
    {
        return DB::table('carts')
            ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
            ->join('sellers', 'carts.seller_id', '=', 'sellers.seller_id')
            ->join('consumers', 'carts.consumer_id', '=', 'consumers.consumer_id')
            ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
            ->select('carts.*', 'payments.status AS status', 'consumers.longitude AS consumer_longitude', 'consumers.latitude AS consumer_latitude', 'consumers.name AS consumer_name', 'consumers.profile_image_url AS consumer_profile_image_url', 'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name', 'sellers.verified', 'sellers.shop_image_url', 'sellers.availability', DB::raw("count(cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.seller_id = ?', [request('seller_id')])
            ->groupBy('carts.id')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $this->updatedLatestConsumerPaymentStatus($user);

        $carts = DB::table('carts')
            ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
            ->join('sellers', 'carts.seller_id', '=', 'sellers.seller_id')
            ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
            ->select('carts.*', 'payments.status AS status', 'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name', 'sellers.verified', 'sellers.shop_image_url', 'sellers.availability', DB::raw("count(cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.consumer_id = ? AND carts.seller_id = ?', [request('consumer_id'), request('seller_id') ])
            ->get();

        $cart = null;
        if (sizeof($carts) > 0 && $carts->first()->id != null) {
            $cart = $carts->first();
            if ($cart->status == 'ACCEPTED' || $cart->status == 'PENDING') {

            } else if ($cart->status == 'SUCCESS' || $cart->status == 'SUCCESSFUL') {
                $cart = $this->createNewCart();
            }
        } else {
            $cart = $this->createNewCart();
        }

        $cart_product = CartProduct::where('cart_id', $cart->cart_id)
            ->where('seller_product_id', request("seller_product_id"))
            ->first();

        if ($cart_product) {
            return Response::json(array(
                'success' => false,
                'cart' => $cart
            ));
        }

        CartProduct::forceCreate(
            [
                'cart_product_id' => Str::uuid(),
                'cart_id' => $cart->cart_id,
                'seller_product_id' => request("seller_product_id"),
                'quantity' => request("quantity"),
                'price' => request("price"),
            ]
        );

        return Response::json(array(
            'success' => true,
            'cart' => $cart
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        return $cart;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $cart->update($request->all());

        $updated_cart = Cart::where('cart_id', $cart->cart_id)->first();

        return response()->json($updated_cart);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function createNewCart()
    {
        $cart_id = Str::uuid();
        Cart::forceCreate(
            [
                'cart_id' => $cart_id,
                'order_id' => random_int(100000000000, 999999999999),
                'seller_id' => request("seller_id"),
                'consumer_id' => request("consumer_id"),
            ]
        );
        return DB::table('carts')
            ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
            ->join('sellers', 'carts.seller_id', '=', 'sellers.seller_id')
            ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
            ->select('carts.*', 'payments.status AS status', 'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name', 'sellers.verified', 'sellers.shop_image_url', 'sellers.availability', DB::raw("count(cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.cart_id = ?', [$cart_id])
            ->get()
            ->first();
    }
}
