<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use App\SellerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class SellerProductController extends Controller
{
    public function scopedSellerProducts()
    {
        return DB::table('seller_products')
            ->join('products', 'seller_products.product_id', '=', 'products.product_id')
            ->join('sellers', 'seller_products.seller_id', '=', 'sellers.seller_id')
            ->select('seller_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url')
            ->where('sellers.seller_id', request('seller_id'))
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
        $SellerProduct = SellerProduct::where('product_id', request('product_id'))
            ->where('seller_id', request('seller_id'))
            ->first();
        if ($SellerProduct) {
            return Response::json(array(
                'already_exists' => true,
            ));
        }
        $seller_product_id = Str::uuid();
        SellerProduct::forceCreate(
            ['seller_product_id' => $seller_product_id] +
            $request->all());

        $seller_products = DB::table('seller_products')
            ->join('products', 'seller_products.product_id', '=', 'products.product_id')
            ->select('seller_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url')
            ->where('seller_product_id', $seller_product_id)
            ->get();

        return Response::json(array(
            'seller_products' => $seller_products,
        ));
    }

    public function proximityProducts(Request $request)
    {
        return DB::table('seller_products')
            ->join('sellers', 'seller_products.seller_id', '=', 'sellers.seller_id')
            ->join('products', 'seller_products.product_id', '=', 'products.product_id')
            ->select('seller_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url', 'sellers.longitude', 'sellers.latitude', 'sellers.shop_name', 'sellers.verified', 'sellers.availability', 'sellers.shop_image_url')
            ->where('products.product_id', request('product_id'))
            ->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SellerProduct  $sellerProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SellerProduct $sellerProduct)
    {
        $SellerProduct = SellerProduct::where('product_id', request('product_id'))
            ->where('seller_id', $sellerProduct->seller_id)
            ->where('seller_product_id', '!=', $sellerProduct->seller_product_id)
            ->first();
        if ($SellerProduct) {
            return Response::json(array(
                'already_exists' => true,
            ));
        }
        $sellerProduct->update($request->all());

        $seller_products = DB::table('seller_products')
            ->join('products', 'seller_products.product_id', '=', 'products.product_id')
            ->select('seller_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url')
            ->where('seller_product_id', $sellerProduct->seller_product_id)
            ->get();

        return Response::json(array(
            'seller_products' => $seller_products,
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SellerProduct  $sellerProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(SellerProduct $sellerProduct)
    {
        $status = $sellerProduct->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
