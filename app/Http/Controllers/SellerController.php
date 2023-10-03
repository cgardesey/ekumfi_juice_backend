<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Consumer;
use App\Seller;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\InstructorSeller;
use App\Payment;
use App\SellerCategory;
use App\Service;
use App\SubscriptionChangeRequest;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class SellerController extends Controller
{

    public function scopedSellers(Request $request)
    {
        $user = User::where('api_token', '=', request('api_token'))->first();

        return DB::table('sellers')
        ->join('agents', 'agents.agent_id', '=', 'sellers.agent_id')
        ->join('users', 'users.user_id', '=', 'agents.user_id')
        ->select('sellers.*')
        ->where('agents.user_id', $user->user_id)
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
        $seller_id = Str::uuid();
        $attributes = [];
        if($request->hasFile("shop_image_file")) {
            // Define folder path
            $folder = '/uploads/shop-images/';// Get image file
            $image = $request->file("shop_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $seller_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $seller_id);

            $attributes = $attributes + ['shop_image_url' => asset('storage/app') . "$filePath"];
        }
        if($request->hasFile("identification_image_file")) {
            // Define folder path
            $folder = '/uploads/seller-identification-images/';// Get image file
            $image = $request->file("identification_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $seller_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $seller_id);

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
                'seller_id' => $seller_id,
                'shop_name' => request('shop_name'),
                'seller_type' => request('seller_type'),
                'primary_contact' => request('primary_contact'),
//                'auxiliary_contact' => request('auxiliary_contact'),
                'momo_number' => request('momo_number'),
                'longitude' => $long,
                'latitude' => $lat,
                'digital_address' => $digital_address,
                'street_address' => $street_address,
                'identification_type' => request('identification_type'),
                'identification_number' => request('identification_number'),
                'agent_id' => request('agent_id'),
                'user_id' => request('user_id')
            ];

        Seller::forceCreate(
            $attributes
        );

        $seller = Seller::where('seller_id', $seller_id)->first();
        $banners = Banner::All();

        return Response::json(array(
            'seller' => $seller,
            'banners' => $banners
        ));
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Seller $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        return $seller;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Seller $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller)
    {
        if (!$request->has("availability")) {
            $attributes = [];
            if ($request->hasFile("shop_image_file")) {
                // Define folder path
                $folder = '/uploads/shop-images/';// Get image file
                $image = $request->file("shop_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $seller->seller_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $seller->seller_id);

                $attributes = $attributes + ['shop_image_url' => asset('storage/app') . "$filePath"];
            }
            if($request->hasFile("identification_image_file")) {
                // Define folder path
                $folder = '/uploads/seller-identification-images/';// Get image file
                $image = $request->file("identification_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $seller->seller_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $seller->seller_id);

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
            /*if (false) {
                $street_address = $decoded_response->data->Table[0]->Street;
                $digital_address = $decoded_response->data->Table[0]->GPSName;
            }*/

            $attributes = $attributes + [
                    'shop_name' => request('shop_name'),
                    'seller_type' => request('seller_type'),
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
            $seller->update(
                $attributes
            );
        } else {
            $seller->update(
                ['availability' => request("availability")]
            );
        }

        return Seller::find($seller->seller_id);
    }
}
