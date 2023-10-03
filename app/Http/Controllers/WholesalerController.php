<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Consumer;
use App\Wholesaler;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\InstructorWholesaler;
use App\Payment;
use App\WholesalerCategory;
use App\Service;
use App\SubscriptionChangeRequest;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class WholesalerController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Wholesaler::All();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $wholesaler_id = Str::uuid();
        $attributes = [];
        if($request->hasFile("shop_image_file")) {
            // Define folder path
            $folder = '/uploads/wholesaler-shop-images/';// Get image file
            $image = $request->file("shop_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $wholesaler_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $wholesaler_id);

            $attributes = $attributes + ['shop_image_url' => asset('storage/app') . "$filePath"];
        }
        if($request->hasFile("identification_image_file")) {
            // Define folder path
            $folder = '/uploads/wholesaler-identification-images/';// Get image file
            $image = $request->file("identification_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $wholesaler_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $wholesaler_id);

            $attributes = $attributes + ['identification_image_url' => asset('storage/app') . "$filePath"];
        }

        $long = request('longitude');
        $lat = request('latitude');

        /*$curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ghanapostgps.sperixlabs.org/get-address',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "long={$long}&lat={$lat}",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $decoded_response = json_decode($response);*/

        $street_address = "Unknown Location";
        $digital_address = "";
        /*if ($decoded_response != null && $decoded_response->found) {
            $street_address = $decoded_response->data->Table[0]->Street;
            $digital_address = $decoded_response->data->Table[0]->GPSName;
        }*/

        $attributes = $attributes + [
                'wholesaler_id' => $wholesaler_id,
                'shop_name' => request('shop_name'),
                'primary_contact' => request('primary_contact'),
//                'auxiliary_contact' => request('auxiliary_contact'),
                'momo_number' => request('momo_number'),
                'longitude' => $long,
                'latitude' => $lat,
                'digital_address' => $digital_address,
                'street_address' => $street_address,
                'identification_type' => request('identification_type'),
                'identification_number' => request('identification_number'),
                'user_id' => request('user_id')
            ];

        Wholesaler::forceCreate(
            $attributes
        );

        $wholesaler = Wholesaler::where('wholesaler_id', $wholesaler_id)->first();
        $banners = Banner::All();

        return Response::json(array(
            'wholesaler' => $wholesaler,
            'banners' => $banners
        ));
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Wholesaler $wholesaler
     * @return \Illuminate\Http\Response
     */
    public function show(Wholesaler $wholesaler)
    {
        return $wholesaler;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Wholesaler $wholesaler
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wholesaler $wholesaler)
    {
        if (!$request->has("availability")) {
            $attributes = [];
            if ($request->hasFile("shop_image_file")) {
                // Define folder path
                $folder = '/uploads/wholesaler-shop-images/';// Get image file
                $image = $request->file("shop_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $wholesaler->wholesaler_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $wholesaler->wholesaler_id);

                $attributes = $attributes + ['shop_image_url' => asset('storage/app') . "$filePath"];
            }
            if($request->hasFile("identification_image_file")) {
                // Define folder path
                $folder = '/uploads/wholesaler-identification-images/';// Get image file
                $image = $request->file("identification_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $wholesaler->wholesaler_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $wholesaler->wholesaler_id);

                $attributes = $attributes + ['identification_image_url' => asset('storage/app') . "$filePath"];
            }

            $long = request('longitude');
            $lat = request('latitude');

            /*$curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://ghanapostgps.sperixlabs.org/get-address',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => "long={$long}&lat={$lat}",
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $decoded_response = json_decode($response);*/

            $street_address = "Unknown Location";
            $digital_address = "";
            /*if ($decoded_response != null && $decoded_response->found) {
                $street_address = $decoded_response->data->Table[0]->Street;
                $digital_address = $decoded_response->data->Table[0]->GPSName;
            }*/

            $attributes = $attributes + [
                    'shop_name' => request('shop_name'),
                    'primary_contact' => request('primary_contact'),
//                    'auxiliary_contact' => request('auxiliary_contact'),
                    'momo_number' => request('momo_number'),
                    'longitude' => $long,
                    'latitude' => $lat,
                    'digital_address' => $digital_address,
                    'street_address' => $street_address,
                    'identification_type' => request('identification_type'),
                    'identification_number' => request('identification_number'),
                    'user_id' => request('user_id')
                ];
            $wholesaler->update(
                $attributes
            );
        } else {
            $wholesaler->update(
                ['availability' => request("availability")]
            );
        }

        return Wholesaler::find($wholesaler->wholesaler_id);
    }
}
