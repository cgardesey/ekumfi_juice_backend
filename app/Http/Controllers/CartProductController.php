<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Cart;
use App\CartProduct;
use App\Payment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class CartProductController extends Controller
{

    public function scopedCartProducts(Request $request)
    {
        return DB::table('cart_products')
            ->join('seller_products', 'seller_products.seller_product_id', '=', 'cart_products.seller_product_id')
            ->join('products', 'products.product_id', '=', 'seller_products.product_id')
            ->select('cart_products.*', 'products.name', 'products.image_url', 'seller_products.unit_quantity', 'seller_products.quantity_available', 'seller_products.unit_price')
            ->where('cart_products.cart_id', $request->has("order_id") ? Cart::where('order_id', request('order_id'))->first()->cart_id : request('cart_id'))
            ->get();
    }

    public function scopedCartTotal(Request $request)
    {
        $payment = Payment::where('cart_id', request('cart_id'))->latest()->first();
        if ($payment) {
            //Check status
            $curl = curl_init();
            $pending_payment_token = hash('sha512', $payment->payment_id . env("MOMO_PAYMENT_API_USERNAME") . env("MOMO_PAYMENT_API_PASSWORD"));
            $clientid = env("MOMO_PAYMENT_API_USERNAME");
            curl_setopt_array($curl, array(
                CURLOPT_URL => env("MOMO_PAYMENT_API_URL"),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\"header\": {\"clientid\": \"{$clientid}\",\"countrycode\": \"{$payment->country_code}\",\"requestid\": \"{$payment->payment_id}\",\"token\": \"{$pending_payment_token}\"},\"requesttype\": \"DEBIT\",\"paymentref\": \"{$payment->payment_ref}\"}",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));

            $status_response = curl_exec($curl);

            Log::info('status_response', [
                'status_response' => $status_response
            ]);

            curl_close($curl);
            //end status check
            if ($status_response != "{}") {
                $decoded_response = json_decode($status_response);

                if ($decoded_response->header->message == 'SUCCESS' && $decoded_response->header->status == '000') {
                    $aupdate_arr = ['status' => $decoded_response->transactionstatus];
                    if (isset($decoded_response->transactionstatusreason)) {
                        $aupdate_arr = $aupdate_arr + ['transactionstatusreason' => $decoded_response->transactionstatusreason];
                    }
                    Payment::find($payment->payment_id)->update(
                        $aupdate_arr
                    );
                }
            }
        }

        $payment = Payment::where('cart_id', request('cart_id'))->first();
        if ($payment) {
            if ($payment->status == 'SUCCESS' || $payment->status == 'SUCCESSFUL') {
                $updated_cart = DB::table('carts')
                    ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
                    ->join('consumers', 'carts.consumer_id', '=', 'consumers.consumer_id')
                    ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
                    ->select('carts.*', 'payments.status AS status', 'consumers.longitude AS consumer_longitude', 'consumers.latitude AS consumer_latitude', 'consumers.name AS consumer_name', 'consumers.profile_image_url AS consumer_profile_image_url', DB::raw("count(cart_products.id) as item_count"))
                    ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.cart_id = ?', [request('cart_id')])
                    ->groupBy('carts.id')
                    ->get();

                return Response::json(array(
                    'updated_cart' => $updated_cart
                ));
            }
        }

        $cart_total =  DB::table('cart_products')
            ->select(DB::raw("sum(cart_products.price) as total_amount"))
            ->where('cart_products.cart_id', request('cart_id'))
            ->get();
        return Response::json(array(
            'cart_total' => $cart_total
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CartProduct  $cartProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CartProduct $cartProduct)
    {
        $cartProduct->update($request->all());

        $updated_cart_product = CartProduct::where('cart_product_id', $cartProduct->cart_product_id)->first();

        return response()->json($updated_cart_product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CartProduct  $cartProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartProduct $cartProduct)
    {
        $status = $cartProduct->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
