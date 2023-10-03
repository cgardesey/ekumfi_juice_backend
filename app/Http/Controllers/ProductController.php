<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Product;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::All();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_id = Str::uuid();
        $attributes = [];
        if($request->hasFile("product_image_file")) {
            // Define folder path
            $folder = '/uploads/product-images/';// Get image file
            $image = $request->file("product_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $product_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $product_id);

            $attributes = $attributes + ['image_url' => asset('storage/app') . "$filePath"];
        }

        $attributes = $attributes + [
                'product_id' => $product_id,
                'name' => request('name'),
                'description' => request('description'),
                'unit_quantity' => request('unit_quantity'),
                'unit_price' => request('unit_price'),
                'quantity_available' => request('quantity_available'),
            ];

        Product::forceCreate(
            $attributes
        );

        return Product::where('product_id', $product_id)->first();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $attributes = [];
        if($request->hasFile("product_image_file")) {
            // Define folder path
            $folder = '/uploads/product-images/';// Get image file
            $image = $request->file("product_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $product->product_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $product->product_id);

            $attributes = $attributes + ['image_url' => asset('storage/app') . "$filePath"];
        }

        $attributes = $attributes + [
                'name' => request('name'),
                'description' => request('description'),
                'unit_quantity' => request('unit_quantity'),
                'unit_price' => request('unit_price'),
                'quantity_available' => request('quantity_available'),
            ];

        $product->update(
            $attributes
        );

        return Product::where('product_id', $product->product_id)->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $status = $product->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
