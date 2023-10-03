<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartProduct;
use App\StockCart;
use App\StockCartProduct;
use App\Payment;
use App\SubscriptionChangeRequest;
use App\Traits\PaymentsTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class StockCartController extends Controller
{
    use PaymentsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return DB::table('stock_carts')
            ->leftJoin('payments', 'stock_carts.stock_cart_id', '=', 'payments.stock_cart_id')
            ->join('agents', 'stock_carts.agent_id', '=', 'agents.agent_id')
            ->join('sellers', 'stock_carts.seller_id', '=', 'sellers.seller_id')
            ->join('stock_cart_products', 'stock_carts.stock_cart_id', '=', 'stock_cart_products.stock_cart_id')
            ->select('stock_carts.*', 'payments.status AS status',
                'agents.longitude AS agent_longitude', 'agents.latitude AS agent_latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names',
                'agents.verified AS agent_verified', 'agents.profile_image_url', 'agents.availability AS agent_availability',

                'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name',
                'sellers.verified AS seller_verified', 'sellers.shop_image_url', 'sellers.availability AS seller_availability',
                DB::raw("count(stock_cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.stock_cart_id))  OR payments.id IS NULL)')
            ->groupBy('stock_carts.id')
            ->get();
    }

    public function scopedStockCarts(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $this->updatedLatestSellerPaymentStatus($user);

        return DB::table('stock_carts')
            ->leftJoin('payments', 'stock_carts.stock_cart_id', '=', 'payments.stock_cart_id')
            ->join('agents', 'stock_carts.agent_id', '=', 'agents.agent_id')
            ->join('sellers', 'stock_carts.seller_id', '=', 'sellers.seller_id')
            ->join('stock_cart_products', 'stock_carts.stock_cart_id', '=', 'stock_cart_products.stock_cart_id')
            ->select('stock_carts.*', 'payments.status AS status',
                'agents.longitude AS agent_longitude', 'agents.latitude AS agent_latitude', 'agents.first_name', 'agents.last_name', 'agents.other_names',
                'agents.verified AS agent_verified', 'agents.profile_image_url', 'agents.availability AS agent_availability',

                'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name',
                'sellers.verified AS seller_verified', 'sellers.shop_image_url', 'sellers.availability AS seller_availability',
                DB::raw("count(stock_cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.stock_cart_id))  OR payments.id IS NULL) AND stock_carts.seller_id = ?', [request("seller_id")])
            ->groupBy('stock_carts.id')
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
        $this->updatedLatestSellerPaymentStatus($user);

        $stock_carts = DB::table('stock_carts')
            ->leftJoin('payments', 'stock_carts.stock_cart_id', '=', 'payments.stock_cart_id')
            ->join('agents', 'stock_carts.agent_id', '=', 'agents.agent_id')
            ->join('sellers', 'stock_carts.seller_id', '=', 'sellers.seller_id')
            ->join('stock_cart_products', 'stock_carts.stock_cart_id', '=', 'stock_cart_products.stock_cart_id')
            ->select('stock_carts.*', 'payments.status AS status',
                'agents.longitude AS agent_longitude', 'agents.latitude AS agent_latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names',
                'agents.verified AS agent_verified', 'agents.profile_image_url', 'agents.availability AS agent_availability',

                'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name',
                'sellers.verified AS seller_verified', 'sellers.shop_image_url', 'sellers.availability AS seller_availability',
                DB::raw("count(stock_cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.stock_cart_id))  OR payments.id IS NULL) AND stock_carts.seller_id = ?', [request('seller_id') ])
            ->get();

        $stock_cart = null;
        if (sizeof($stock_carts) > 0 && $stock_carts->first()->id != null) {
            $stock_cart = $stock_carts->first();
            if ($stock_cart->status == 'ACCEPTED' || $stock_cart->status == 'PENDING') {

            } else if ($stock_cart->status == 'SUCCESS' || $stock_cart->status == 'SUCCESSFUL') {
                $stock_cart = $this->createNewStockCart();
            }
        } else {
            $stock_cart = $this->createNewStockCart();
        }

        $stock_cart_product = StockCartProduct::where('stock_cart_id', $stock_cart->stock_cart_id)
            ->where('agent_product_id', request("agent_product_id"))
            ->first();

        if ($stock_cart_product) {
            return Response::json(array(
                'success' => false,
                'stock_cart' => $stock_cart,
            ));
        }

        StockCartProduct::forceCreate(
            [
                'stock_cart_product_id' => Str::uuid(),
                'stock_cart_id' => $stock_cart->stock_cart_id,
                'agent_product_id' => request("agent_product_id"),
                'quantity' => request("quantity"),
                'price' => request("price"),
            ]
        );

        return Response::json(array(
            'success' => true,
            'stock_cart' => $stock_cart
        ));
    }


    /**
     * Display the specified resource.
     *
     * @param \App\StockCart $StockCart
     * @return \Illuminate\Http\Response
     */
    public function show(StockCart $StockCart)
    {
        return $StockCart;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\StockCart $StockCart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockCart $StockCart)
    {
        $StockCart->update($request->all());

        $updated_StockCart = StockCart::where('stock_cart_id', $StockCart->stock_cart_id)->first();

        return response()->json($updated_StockCart);
    }

    public function createNewStockCart()
    {
        $stock_cart_id = Str::uuid();
        StockCart::forceCreate(
            [
                'stock_cart_id' => $stock_cart_id,
                'order_id' => random_int(100000000000, 999999999999),
                'seller_id' => request("seller_id"),
                'agent_id' => request("agent_id")
            ]
        );

        return DB::table('stock_carts')
            ->leftJoin('payments', 'stock_carts.stock_cart_id', '=', 'payments.stock_cart_id')
            ->join('agents', 'stock_carts.agent_id', '=', 'agents.agent_id')
            ->join('sellers', 'stock_carts.seller_id', '=', 'sellers.seller_id')
            ->join('stock_cart_products', 'stock_carts.stock_cart_id', '=', 'stock_cart_products.stock_cart_id')
            ->select('stock_carts.*', 'payments.status AS status',
                'agents.longitude AS agent_longitude', 'agents.latitude AS agent_latitude', 'agents.first_name', 'agents.last_name', 'agents.other_names',
                'agents.verified AS agent_verified', 'agents.profile_image_url', 'agents.availability AS agent_availability',

                'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name',
                'sellers.verified AS seller_verified', 'sellers.shop_image_url', 'sellers.availability AS seller_availability',
                DB::raw("count(stock_cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.stock_cart_id))  OR payments.id IS NULL) AND stock_carts.stock_cart_id = ?', [$stock_cart_id])

            ->get()
            ->first();
    }
}
