<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Consumer;
use App\EkumfiInfo;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\InstructorEkumfiInfo;
use App\Payment;
use App\EkumfiInfoCategory;
use App\Service;
use App\SubscriptionChangeRequest;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class EkumfiInfoController extends Controller
{
    use UploadTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ekumfi_info_id = Str::uuid();
        $attributes = [];
        if($request->hasFile("profile_image_file")) {
            // Define folder path
            $folder = '/uploads/';// Get image file
            $image = $request->file("profile_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $ekumfi_info_id . '.' . $image->getClientOriginalExtension();// Upload image
            $this->uploadOne($image, $folder, '', $ekumfi_info_id);

            $attributes = $attributes + ['profile_image_url' => asset('storage/app') . "$filePath"];
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
                'ekumfi_info_id' => $ekumfi_info_id,
                'name' => request('name'),
                'primary_contact' => request('primary_contact'),
//                'auxiliary_contact' => request('auxiliary_contact'),
                'longitude' => $long,
                'latitude' => $lat,
                'digital_address' => $digital_address,
                'street_address' => $street_address,
                'user_id' => request('user_id')
            ];

        EkumfiInfo::forceCreate(
            $attributes
        );

        $ekumfiInfo = EkumfiInfo::where('ekumfi_info_id', $ekumfi_info_id)->first();
        $banners = Banner::All();

        return Response::json(array(
            'ekumfi_info' => $ekumfiInfo,
            'banners' => $banners
        ));
    }


    /**
     * Display the specified resource.
     *
     * @param \App\EkumfiInfo $ekumfiInfo
     * @return \Illuminate\Http\Response
     */
    public function show(EkumfiInfo $ekumfiInfo)
    {
        return $ekumfiInfo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\EkumfiInfo $ekumfiInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EkumfiInfo $ekumfiInfo)
    {
        if (!$request->has("availability")) {
            $attributes = [];
            if ($request->hasFile("profile_image_file")) {
                // Define folder path
                $folder = '/uploads/';// Get image file
                $image = $request->file("profile_image_file");// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $ekumfiInfo->ekumfi_info_id . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $ekumfiInfo->ekumfi_info_id);

                $attributes = $attributes + ['profile_image_url' => asset('storage/app') . "$filePath"];
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
                    'name' => request('name'),
                    'primary_contact' => request('primary_contact'),
//                    'auxiliary_contact' => request('auxiliary_contact'),
                    'longitude' => $long,
                    'latitude' => $lat,
                    'digital_address' => $digital_address,
                    'street_address' => $street_address,
                    'user_id' => request('user_id')
                ];
            $ekumfiInfo->update(
                $attributes
            );
        } else {
            $ekumfiInfo->update(
                ['availability' => request("availability")]
            );
        }

        return EkumfiInfo::find($ekumfiInfo->ekumfi_info_id);
    }
}
