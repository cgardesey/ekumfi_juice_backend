<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Cart;
use App\StockCart;
use App\StockCartProduct;
use App\Payment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class StockCartProductController extends Controller
{
    public function scopedStockCartProducts(Request $request)
    {
        return DB::table('stock_cart_products')
            ->join('agent_products', 'agent_products.agent_product_id', '=', 'stock_cart_products.agent_product_id')
            ->join('products', 'products.product_id', '=', 'agent_products.product_id')
            ->select('stock_cart_products.*', 'products.name', 'products.image_url', 'agent_products.unit_quantity', 'agent_products.quantity_available', 'agent_products.unit_price')
            ->where('stock_cart_products.stock_cart_id', $request->has("order_id") ? Cart::where('order_id', request('order_id'))->first()->cart_id : request('stock_cart_id'))
            ->get();
    }

    public function scopedStockCartTotal(Request $request)
    {
        $payment = Payment::where('stock_cart_id', request('stock_cart_id'))->latest()->first();
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
                'status_response_stock' => $status_response
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

        $payment = Payment::where('stock_cart_id', request('stock_cart_id'))->first();
        if ($payment) {
            if ($payment->status == 'SUCCESS' || $payment->status == 'SUCCESSFUL') {
                $updated_stock_cart = DB::table('stock_carts')
                    ->leftJoin('payments', 'stock_carts.stock_cart_id', '=', 'payments.stock_cart_id')
                    ->join('stock_cart_products', 'stock_carts.stock_cart_id', '=', 'stock_cart_products.stock_cart_id')
                    ->select('stock_carts.*', 'payments.status AS status', DB::raw("count(stock_cart_products.id) as item_count"))
                    ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.stock_cart_id))  OR payments.id IS NULL) AND stock_carts.stock_cart_id = ?', [request('stock_cart_id')])
                    ->groupBy('stock_carts.id')
                    ->get();

                return Response::json(array(
                    'updated_stock_cart' => $updated_stock_cart
                ));
            }
        }

        $stock_cart_total =  DB::table('stock_cart_products')
            ->select(DB::raw("sum(stock_cart_products.price) as total_amount"))
            ->where('stock_cart_products.stock_cart_id', request('stock_cart_id'))
            ->get();
        return Response::json(array(
            'stock_cart_total' => $stock_cart_total
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockCartProduct  $stockCartProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockCartProduct $stockCartProduct)
    {
        $stockCartProduct->update($request->all());

        $updated_cart_product = StockCartProduct::where('stock_cart_product_id', $stockCartProduct->stock_cart_product_id)->first();

        return response()->json($updated_cart_product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockCartProduct  $stockCartProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockCartProduct $stockCartProduct)
    {
        $status = $stockCartProduct->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
