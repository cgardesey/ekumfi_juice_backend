<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartProduct;
use App\WholesalerCart;
use App\WholesalerCartProduct;
use App\Payment;
use App\SubscriptionChangeRequest;
use App\Traits\PaymentsTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class WholesalerCartController extends Controller
{
    use PaymentsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return DB::table('wholesaler_carts')
            ->leftJoin('payments', 'wholesaler_carts.wholesaler_cart_id', '=', 'payments.wholesaler_cart_id')
            ->join('wholesalers', 'wholesaler_carts.wholesaler_id', '=', 'wholesalers.wholesaler_id')
            ->join('agents', 'wholesaler_carts.agent_id', '=', 'agents.agent_id')
            ->join('wholesaler_cart_products', 'wholesaler_carts.wholesaler_cart_id', '=', 'wholesaler_cart_products.wholesaler_cart_id')
            ->select('wholesaler_carts.*', 'payments.status AS status',
                'wholesalers.longitude AS wholesaler_longitude', 'wholesalers.latitude AS wholesaler_latitude', 'wholesalers.shop_name',
                'wholesalers.verified AS wholesaler_verified', 'wholesalers.shop_image_url', 'wholesalers.availability AS wholesaler_availability',

                'agents.longitude AS agent_longitude', 'agents.latitude AS agent_latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names',
                'agents.verified AS agent_verified', 'agents.profile_image_url', 'agents.availability AS agent_availability',
                DB::raw("count(wholesaler_cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.wholesaler_cart_id))  OR payments.id IS NULL)')
            ->groupBy('wholesaler_carts.id')
            ->get();
    }

    public function scopedWholesalerCarts(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $this->updatedLatestAgentPaymentStatus($user);

        return DB::table('wholesaler_carts')
            ->leftJoin('payments', 'wholesaler_carts.wholesaler_cart_id', '=', 'payments.wholesaler_cart_id')
            ->join('wholesalers', 'wholesaler_carts.wholesaler_id', '=', 'wholesalers.wholesaler_id')
            ->join('agents', 'wholesaler_carts.agent_id', '=', 'agents.agent_id')
            ->join('wholesaler_cart_products', 'wholesaler_carts.wholesaler_cart_id', '=', 'wholesaler_cart_products.wholesaler_cart_id')
            ->select('wholesaler_carts.*', 'payments.status AS status',
                'wholesalers.longitude AS wholesaler_longitude', 'wholesalers.latitude AS wholesaler_latitude', 'wholesalers.shop_name',
                'wholesalers.verified AS wholesaler_verified', 'wholesalers.shop_image_url', 'wholesalers.availability AS wholesaler_availability',

                'agents.longitude AS agent_longitude', 'agents.latitude AS agent_latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names',
                'agents.verified AS agent_verified', 'agents.profile_image_url', 'agents.availability AS agent_availability',
                DB::raw("count(wholesaler_cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.wholesaler_cart_id))  OR payments.id IS NULL) AND wholesaler_carts.agent_id = ?', [request("agent_id")])
            ->groupBy('wholesaler_carts.id')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /*public function store(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $this->updatedLatestAgentPaymentStatus($user);

        $wholesaler_carts = DB::table('wholesaler_carts')
            ->leftJoin('payments', 'wholesaler_carts.wholesaler_cart_id', '=', 'payments.wholesaler_cart_id')
            ->join('wholesalers', 'wholesaler_carts.wholesaler_id', '=', 'wholesalers.wholesaler_id')
            ->join('agents', 'wholesaler_carts.agent_id', '=', 'agents.agent_id')
            ->join('wholesaler_cart_products', 'wholesaler_carts.wholesaler_cart_id', '=', 'wholesaler_cart_products.wholesaler_cart_id')
            ->select('wholesaler_carts.*', 'payments.status AS status',
                'wholesalers.longitude AS wholesaler_longitude', 'wholesalers.latitude AS wholesaler_latitude', 'wholesalers.shop_name',
                'wholesalers.verified AS wholesaler_verified', 'wholesalers.shop_image_url', 'wholesalers.availability AS wholesaler_availability',

                'agents.longitude AS agent_longitude', 'agents.latitude AS agent_latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names',
                'agents.verified AS agent_verified', 'agents.profile_image_url', 'agents.availability AS agent_availability',
                DB::raw("count(wholesaler_cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.wholesaler_cart_id))  OR payments.id IS NULL) AND wholesaler_carts.agent_id = ?', [request('agent_id') ])
            ->get();

        $wholesaler_cart = null;
        if (sizeof($wholesaler_carts) > 0 && $wholesaler_carts->first()->id != null) {
            $wholesaler_cart = $wholesaler_carts->first();
            if ($wholesaler_cart->status == 'ACCEPTED' || $wholesaler_cart->status == 'PENDING') {

            } else if ($wholesaler_cart->status == 'SUCCESS' || $wholesaler_cart->status == 'SUCCESSFUL') {
                $wholesaler_cart = $this->createNewWholesalerCart();
            }
        } else {
            $wholesaler_cart = $this->createNewWholesalerCart();
        }

        $wholesaler_cart_product = WholesalerCartProduct::where('wholesaler_cart_id', $wholesaler_cart->wholesaler_cart_id)
            ->where('wholesaler_product_id', request("wholesaler_product_id"))
            ->first();

        if ($wholesaler_cart_product) {
            return Response::json(array(
                'success' => false,
                'wholesaler_cart' => $wholesaler_cart,
            ));
        }

        WholesalerCartProduct::forceCreate(
            [
                'wholesaler_cart_product_id' => Str::uuid(),
                'wholesaler_cart_id' => $wholesaler_cart->wholesaler_cart_id,
                'wholesaler_product_id' => request("wholesaler_product_id"),
                'quantity' => request("quantity"),
                'price' => request("price"),
            ]
        );

        return Response::json(array(
            'success' => true,
            'wholesaler_cart' => $wholesaler_cart
        ));
    }*/

    /**
     * Display the specified resource.
     *
     * @param \App\WholesalerCart $WholesalerCart
     * @return \Illuminate\Http\Response
     */
    public function show(WholesalerCart $WholesalerCart)
    {
        return $WholesalerCart;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\WholesalerCart $WholesalerCart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WholesalerCart $WholesalerCart)
    {
        $WholesalerCart->update($request->all());

        $updated_WholesalerCart = WholesalerCart::where('wholesaler_cart_id', $WholesalerCart->wholesaler_cart_id)->first();

        return response()->json($updated_WholesalerCart);
    }

    public function createNewWholesalerCart()
    {
        $wholesaler_cart_id = Str::uuid();
        WholesalerCart::forceCreate(
            [
                'wholesaler_cart_id' => $wholesaler_cart_id,
                'order_id' => random_int(100000000000, 999999999999),
                'agent_id' => request("agent_id"),
                'wholesaler_id' => request("wholesaler_id")
            ]
        );

        return DB::table('wholesaler_carts')
            ->leftJoin('payments', 'wholesaler_carts.wholesaler_cart_id', '=', 'payments.wholesaler_cart_id')
            ->join('wholesalers', 'wholesaler_carts.wholesaler_id', '=', 'wholesalers.wholesaler_id')
            ->join('agents', 'wholesaler_carts.agent_id', '=', 'agents.agent_id')
            ->join('wholesaler_cart_products', 'wholesaler_carts.wholesaler_cart_id', '=', 'wholesaler_cart_products.wholesaler_cart_id')
            ->select('wholesaler_carts.*', 'payments.status AS status',
                'wholesalers.longitude AS wholesaler_longitude', 'wholesalers.latitude AS wholesaler_latitude', 'wholesalers.shop_name',
                'wholesalers.verified AS wholesaler_verified', 'wholesalers.shop_image_url', 'wholesalers.availability AS wholesaler_availability',

                'agents.longitude AS agent_longitude', 'agents.latitude AS agent_latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names',
                'agents.verified AS agent_verified', 'agents.profile_image_url', 'agents.availability AS agent_availability',
                DB::raw("count(wholesaler_cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.wholesaler_cart_id))  OR payments.id IS NULL) AND wholesaler_carts.wholesaler_cart_id = ?', [$wholesaler_cart_id])

            ->get()
            ->first();
    }
}
