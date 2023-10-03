<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use App\WholesalerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class WholesalerProductController extends Controller
{
    public function scopedWholesalerProducts()
    {
        return DB::table('wholesaler_products')
            ->join('wholesalers', 'wholesaler_products.wholesaler_id', '=', 'wholesalers.wholesaler_id')
            ->join('products', 'wholesaler_products.product_id', '=', 'products.product_id')
            ->select('wholesaler_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url', 'wholesalers.longitude', 'wholesalers.latitude', 'wholesalers.shop_name', 'wholesalers.verified', 'wholesalers.availability', 'wholesalers.shop_image_url')
            ->where('wholesalers.wholesaler_id', request('wholesaler_id'))
            ->get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $WholesalerProduct = WholesalerProduct::where('product_id', request('product_id'))
            ->where('wholesaler_id', request('wholesaler_id'))
            ->first();
        if ($WholesalerProduct) {
            return Response::json(array(
                'already_exists' => true,
            ));
        }
        $wholesaler_product_id = Str::uuid();
        WholesalerProduct::forceCreate(
            ['wholesaler_product_id' => $wholesaler_product_id] +
            $request->all());

        $wholesaler_products = DB::table('wholesaler_products')
            ->join('wholesalers', 'wholesaler_products.wholesaler_id', '=', 'wholesalers.wholesaler_id')
            ->join('products', 'wholesaler_products.product_id', '=', 'products.product_id')
            ->select('wholesaler_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url', 'wholesalers.longitude', 'wholesalers.latitude', 'wholesalers.shop_name', 'wholesalers.verified', 'wholesalers.availability', 'wholesalers.shop_image_url')
            ->where('wholesaler_product_id', $wholesaler_product_id)
            ->get();

        return Response::json(array(
            'wholesaler_products' => $wholesaler_products,
        ));
    }

    public function proximityProducts(Request $request)
    {
        return DB::table('wholesaler_products')
            ->join('wholesalers', 'wholesaler_products.wholesaler_id', '=', 'wholesalers.wholesaler_id')
            ->join('products', 'wholesaler_products.product_id', '=', 'products.product_id')
            ->select('wholesaler_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url', 'wholesalers.longitude', 'wholesalers.latitude', 'wholesalers.shop_name', 'wholesalers.verified', 'wholesalers.availability', 'wholesalers.shop_image_url')
            ->where('products.product_id', request('product_id'))
            ->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WholesalerProduct  $wholesalerProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WholesalerProduct $wholesalerProduct)
    {
        $WholesalerProduct = WholesalerProduct::where('product_id', request('product_id'))
            ->where('wholesaler_id', $wholesalerProduct->wholesaler_id)
            ->where('wholesaler_product_id', '!=', $wholesalerProduct->wholesaler_product_id)
            ->first();
        if ($WholesalerProduct) {
            return Response::json(array(
                'already_exists' => true,
            ));
        }
        $wholesalerProduct->update($request->all());

        $wholesaler_products = DB::table('wholesaler_products')
            ->join('wholesalers', 'wholesaler_products.wholesaler_id', '=', 'wholesalers.wholesaler_id')
            ->join('products', 'wholesaler_products.product_id', '=', 'products.product_id')
            ->select('wholesaler_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url', 'wholesalers.longitude', 'wholesalers.latitude', 'wholesalers.shop_name', 'wholesalers.verified', 'wholesalers.availability', 'wholesalers.shop_image_url')
            ->where('wholesaler_product_id', $wholesalerProduct->wholesaler_product_id)
            ->get();

        return Response::json(array(
            'wholesaler_products' => $wholesaler_products,
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WholesalerProduct  $wholesalerProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(WholesalerProduct $wholesalerProduct)
    {
        $status = $wholesalerProduct->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
