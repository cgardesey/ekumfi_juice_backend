<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    use UploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Banner::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $banner_id = Str::uuid();
        $attributes = [];
        if($request->hasFile("banner_image_file")) {
            // Define folder path
            $folder = '/uploads/banner-images/';// Get image file
            $image = $request->file("banner_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $banner_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $banner_id);

            $attributes = $attributes + ['url' => asset('storage/app') . "$filePath"];
        }

        $attributes = $attributes + [
                'banner_id' => $banner_id,
            ];

        Banner::forceCreate(
            $attributes
        );

        return Banner::where('banner_id', $banner_id)->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        $status = $banner->delete();
        return Response::json(array(
            'status' => $status
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\banner $banner
     * @return \Illuminate\Http\Response
     */
    public function show(banner $banner)
    {
        return $banner;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\banner $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, banner $banner)
    {
        $banner->update($request->all());

        $updated_banner = Banner::where('banner_id', $banner->banner_id)->first();

        return response()->json($updated_banner);
    }

}
