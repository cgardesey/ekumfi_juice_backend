<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Banner;
use App\Cart;
use App\Chat;
use App\Consumer;
use App\EkumfiInfo;
use App\Product;
use App\ProductCategory;
use App\Seller;
use App\SellerCategory;
use App\Service;
use App\ServiceCategory;
use App\Student;
use App\User;
use App\Enrolment;
use App\Institution;
use App\InstitutionFee;
use App\InstructorUser;
use App\Payment;
use App\SubscriptionChangeRequest;
use App\Wholesaler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $role = $user->role;

        switch ($role) {
            case 'admin':
                return User::all();
            case 'instructor':
                return $user->info->users;
            case 'student':
//                return User::setEagerLoads([])->get();
                $instructor_users = $user->info->instructorUsers;
                $users = [];
                foreach ($instructor_users as $instructor_user) {
                    $users[] = $instructor_user->user;
                }
                return $users;
            default:
                'default';
                break;
        }
    }

    public function adminLogin(Request $request)
    {

        $user = User::where('email', request('email'))->first();

        if (!$user) {
            return response()->json(array(
                'user_not_found' => true
            ));
        } elseif (Hash::check(request('password'), $user->password)) {
            $banners = Banner::all();
            $products = Product::all();

            $stock_carts = DB::table('stock_carts')
                ->leftJoin('payments', 'stock_carts.stock_cart_id', '=', 'payments.stock_cart_id')
                ->join('sellers', 'stock_carts.seller_id', '=', 'sellers.seller_id')
                ->join('stock_cart_products', 'stock_carts.stock_cart_id', '=', 'stock_cart_products.stock_cart_id')
                ->select('stock_carts.*', 'payments.status AS status', 'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name', 'sellers.verified', 'sellers.shop_image_url', 'sellers.availability', DB::raw("count(stock_cart_products.id) as item_count"))
                ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.stock_cart_id)) OR payments.id IS NULL)')
                ->groupBy('stock_carts.id')
                ->get();

            /*$ekumfi_chats = DB::table('chats')
                ->whereRaw('id IN (SELECT MAX(id) FROM chats GROUP BY seller_id) AND consumer_id = ?', [''])
                ->get();*/

            $sub = Chat::where('consumer_id', "");
            $ekumfi_chats = DB::table(DB::raw("({$sub->toSql()}) as sub"))
                ->mergeBindings($sub->getQuery()) // you need to get underlying Query Builder
                ->select('*')
                ->whereRaw('id IN (SELECT MAX(id) FROM chats GROUP BY seller_id)')
                ->get();

            return response()->json(array(
                'api_token' => $user->api_token,
                'ekumfi_infos' => EkumfiInfo::all(),
                'sellers' => Seller::all(),
                'agents' => Agent::all(),
                'banners' => $banners,
                'products' => $products,
                'stock_carts' => $stock_carts,
                'ekumfi_chats' => $ekumfi_chats,
            ));
        } else {
            return response()->json(array(
                'incorrect_password' => true
            ));
        }
    }

    public function sendOtp(Request $request)
    {
        $phone_number = request('phone_number');

        $user = User::where('phone_number', $phone_number)->first();

        if (!$user) {
            $userid = Str::uuid();
            $user = User::forceCreate([
                'user_id' => $userid,
                'phone_number' => $phone_number,
                'api_token' => Str::uuid()
            ]);
        }

        if ($request->has('agent_id')) {
            $seller = Seller::where('user_id', $user->user_id)->first();
            if ($seller != null && $seller->agent_id != request('agent_id')) {
                return response()->json(array(
                    'registered_by_another_agent' => true
                ));
            }
        }

        $hash = request('hash');
        $client = new Client();
        $otp = mt_rand(1000, 9999);
//        $content = "<#> Your OTP is: $otp $hash";
//        $content = urlencode($content);

        $user->update([
            'otp' => $otp
        ]);

        $this->sendSmsGuzzleRequest($phone_number, "Your pin is: {$otp}", new Client());

        return response()->json(array(
            'otp' => $otp
        ));
    }

    public function getOtp(Request $request)
    {
        $user = User::where('phone_number', '=', request('phone_number'))->first();
        $otp = $user->otp;
        $seller = Seller::where('user_id', $user->user_id)->first();
        $consumer = Consumer::where('user_id', $user->user_id)->first();
        $banners = Banner::all();
        $products = Product::all();

        $seller_products = DB::table('seller_products')
            ->join('products', 'seller_products.product_id', '=', 'products.product_id')
            ->join('sellers', 'seller_products.seller_id', '=', 'sellers.seller_id')
            ->select('seller_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url')
            ->where('sellers.user_id', $user->user_id)
            ->get();

        $scoped_consumer_carts = [];
        $scoped_seller_carts = [];
        $scoped_stock_carts = [];
        $seller_chat_with_consumers = [];
        $seller_chat_with_agents = [];
        $consumer_chats = [];

        if ($consumer) {
            $scoped_consumer_carts = DB::table('carts')
                ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
                ->join('sellers', 'carts.seller_id', '=', 'sellers.seller_id')
                ->join('consumers', 'carts.consumer_id', '=', 'consumers.consumer_id')
                ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
                ->select('carts.*', 'payments.status AS status', 'consumers.longitude AS consumer_longitude', 'consumers.latitude AS consumer_latitude', 'consumers.name AS consumer_name', 'consumers.profile_image_url AS consumer_profile_image_url', 'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name', 'sellers.verified', 'sellers.shop_image_url', 'sellers.availability', DB::raw("count(cart_products.id) as item_count"))
                ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.consumer_id = ?', [$consumer->consumer_id])
                ->groupBy('carts.id')
                ->get();

            $sub_chat_with_consumers = Chat::where('consumer_id', $consumer->consumer_id);
            $consumer_chats = DB::table(DB::raw("({$sub_chat_with_consumers->toSql()}) as sub"))
                ->mergeBindings($sub_chat_with_consumers->getQuery()) // you need to get underlying Query Builder
                ->select('*')
                ->whereRaw('id IN (SELECT MAX(id) FROM chats GROUP BY seller_id)')
                ->get();
        }
        if ($seller) {
            $scoped_seller_carts = DB::table('carts')
                ->leftJoin('payments', 'carts.cart_id', '=', 'payments.cart_id')
                ->join('sellers', 'carts.seller_id', '=', 'sellers.seller_id')
                ->join('consumers', 'carts.consumer_id', '=', 'consumers.consumer_id')
                ->join('cart_products', 'carts.cart_id', '=', 'cart_products.cart_id')
                ->select('carts.*', 'payments.status AS status', 'consumers.longitude AS consumer_longitude', 'consumers.latitude AS consumer_latitude', 'consumers.name AS consumer_name', 'consumers.profile_image_url AS consumer_profile_image_url', 'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name', 'sellers.verified', 'sellers.shop_image_url', 'sellers.availability', DB::raw("count(cart_products.id) as item_count"))
                ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.cart_id)) OR payments.id IS NULL) AND carts.seller_id = ?', [$seller->seller_id])
                ->groupBy('carts.id')
                ->get();

            $scoped_stock_carts = DB::table('stock_carts')
                ->leftJoin('payments', 'stock_carts.stock_cart_id', '=', 'payments.stock_cart_id')
                ->join('agents', 'stock_carts.agent_id', '=', 'agents.agent_id')
                ->join('sellers', 'stock_carts.seller_id', '=', 'sellers.seller_id')
                ->join('stock_cart_products', 'stock_carts.stock_cart_id', '=', 'stock_cart_products.stock_cart_id')
                ->select('stock_carts.*', 'payments.status AS status',
                    'agents.longitude AS agent_longitude', 'agents.latitude AS agent_latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names',
                    'agents.verified AS agent_verified', 'agents.profile_image_url', 'agents.availability AS agent_availability',

                    'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name',
                    'sellers.verified AS seller_verified', 'sellers.shop_image_url', 'sellers.availability AS seller_availability',
                    DB::raw("count(stock_cart_products.id) as item_count"))
                ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.stock_cart_id))  OR payments.id IS NULL) AND stock_carts.seller_id = ?', [$seller->seller_id])
                ->groupBy('stock_carts.id')
                ->get();

            $sub_chat_with_consumers = Chat::where('seller_id', $seller->seller_id);
            $seller_chat_with_consumers = DB::table(DB::raw("({$sub_chat_with_consumers->toSql()}) as sub"))
                ->mergeBindings($sub_chat_with_consumers->getQuery()) // you need to get underlying Query Builder
                ->select('*')
                ->whereRaw('id IN (SELECT MAX(id) FROM chats GROUP BY consumer_id)')
                ->get();

            $sub_chat_with_agents = Chat::where('seller_id', $seller->seller_id);
            $seller_chat_with_agents = DB::table(DB::raw("({$sub_chat_with_agents->toSql()}) as sub"))
                ->mergeBindings($sub_chat_with_agents->getQuery()) // you need to get underlying Query Builder
                ->select('*')
                ->whereRaw('id IN (SELECT MAX(id) FROM chats GROUP BY agent_id)')
                ->get();
        }

        return response()->json(array(
            'otp' => $otp,
            'user_id' => $user->user_id,
            'api_token' => $user->api_token,
            'user' => $user,
            'ekumfi_infos' => EkumfiInfo::all(),
            'consumer' => $consumer,
            'sellers' => Seller::all(),
            //////////////////////'agents' => Seller::all(),
            'banners' => $banners,
            'products' => $products,
            'seller_products' => $seller_products,
            'scoped_consumer_carts' => $scoped_consumer_carts,
            'scoped_seller_carts' => $scoped_seller_carts,
            'scoped_stock_carts' => $scoped_stock_carts,
            'consumer_chats' => $consumer_chats,
            'seller_chat_with_consumers' => $seller_chat_with_consumers,
            'seller_chat_with_agents' => $seller_chat_with_agents,
        ));
    }

    public function vendorGetOtp(Request $request)
    {
        $user = User::where('phone_number', '=', request('phone_number'))->first();
        $otp = $user->otp;
        $seller = Seller::where('user_id', $user->user_id)->first();

        return response()->json(array(
            'otp' => $otp,
            'user_id' => $user->user_id,
            'seller' => $seller
        ));
    }

    public function agentGetOtp(Request $request)
    {
        $user = User::where('phone_number', '=', request('phone_number'))->first();

        return response()->json(array(
            'otp' => $user->otp,
            'user_id' => $user->user_id,
            'api_token' => $user->api_token,
            'banners' => Banner::all(),
            'products' => Product::all(),
            'agent' => Agent::where('user_id', $user->user_id)->first(),
            'agent_products' => DB::table('agent_products')
                ->join('agents', 'agent_products.agent_id', '=', 'agents.agent_id')
                ->join('products', 'agent_products.product_id', '=', 'products.product_id')
                ->select('agent_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url', 'agents.longitude', 'agents.latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names', 'agents.verified', 'agents.availability', 'agents.profile_image_url')
                ->where('agents.user_id', $user->user_id)
                ->get(),
            'sellers' => DB::table('sellers')
                ->join('agents', 'agents.agent_id', '=', 'sellers.agent_id')
                ->join('users', 'users.user_id', '=', 'agents.user_id')
                ->select('sellers.*')
                ->where('agents.user_id', $user->user_id)
                ->get(),
        ));
    }

    public function wholesalerGetOtp(Request $request)
    {
        $user = User::where('phone_number', '=', request('phone_number'))->first();

        return response()->json(array(
            'otp' => $user->otp,
            'user_id' => $user->user_id,
            'api_token' => $user->api_token,
            'wholesaler' => Wholesaler::where('user_id', $user->user_id)->first(),
            'wholesaler_products' => DB::table('wholesaler_products')
                ->join('wholesalers', 'wholesaler_products.wholesaler_id', '=', 'wholesalers.wholesaler_id')
                ->join('products', 'wholesaler_products.product_id', '=', 'products.product_id')
                ->select('wholesaler_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url', 'wholesalers.longitude', 'wholesalers.latitude', 'wholesalers.shop_name', 'wholesalers.verified', 'wholesalers.availability', 'wholesalers.shop_image_url')
                ->where('wholesalers.user_id', $user->user_id)
                ->get(),
            'agents' => DB::table('agents')
                ->join('wholesalers', 'wholesalers.wholesaler_id', '=', 'wholesalers.wholesaler_id')
                ->join('users', 'users.user_id', '=', 'agents.user_id')
                ->select('agents.*')
                ->where('wholesalers.user_id', $user->user_id)
                ->get(),
        ));
    }

    public function scopedAgentGetOtp(Request $request)
    {
        $user = User::where('phone_number', '=', request('phone_number'))->first();
        $otp = $user->otp;
        $agents[] = Agent::where('user_id', $user->user_id)->first();
        $banners = Banner::all();
        $products = Product::all();
        $stock_carts = DB::table('stock_carts')
            ->leftJoin('payments', 'stock_carts.stock_cart_id', '=', 'payments.stock_cart_id')
            ->join('sellers', 'stock_carts.seller_id', '=', 'sellers.seller_id')
            ->join('stock_cart_products', 'stock_carts.stock_cart_id', '=', 'stock_cart_products.stock_cart_id')
            ->select('stock_carts.*', 'payments.status AS status', 'sellers.longitude AS seller_longitude', 'sellers.latitude AS seller_latitude', 'sellers.shop_name', 'sellers.verified', 'sellers.shop_image_url', 'sellers.availability', DB::raw("count(stock_cart_products.id) as item_count"))
            ->whereRaw('(payments.id in (select max(payments.id) from payments group by (payments.stock_cart_id)) OR payments.id IS NULL)')
            ->groupBy('stock_carts.id')
            ->get();

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
            ->groupBy('wholesaler_carts.id')
            ->get();

        /*$ekumfi_chats = DB::table('chats')
            ->whereRaw('id IN (SELECT MAX(id) FROM chats GROUP BY seller_id) AND consumer_id = ?', [''])
            ->get();*/

        $sub = Chat::where('consumer_id', "");
        $ekumfi_chats = DB::table(DB::raw("({$sub->toSql()}) as sub"))
            ->mergeBindings($sub->getQuery()) // you need to get underlying Query Builder
            ->select('*')
            ->whereRaw('id IN (SELECT MAX(id) FROM chats GROUP BY seller_id)')
            ->get();

        return response()->json(array(
            'otp' => $otp,
            'user_id' => $user->user_id,
            'api_token' => $user->api_token,
            'seller' =>  Seller::all(),
            'ekumfi_infos' => EkumfiInfo::all(),
            'sellers' => DB::table('sellers')
                ->join('agents', 'agents.agent_id', '=', 'sellers.agent_id')
                ->join('users', 'users.user_id', '=', 'agents.user_id')
                ->select('sellers.*')
                ->where('agents.user_id', $user->user_id)
                ->get(),
            'agents' => Agent::where('user_id', $user->user_id)->get(),
            'banners' => $banners,
            'products' => $products,
            'stock_carts' => $stock_carts,
            'ekumfi_chats' => $ekumfi_chats,
        ));
    }

    public function changeNumberSendOtp()
    {
        $old_phone_number = request('old_phone_number');
        $new_phone_number = request('new_phone_number');

        $user = User::where('phone_number', $old_phone_number)->first();

        if (User::where('phone_number', $new_phone_number)->first()) {
            return response()->json(array(
                'new_number_taken' => true,
            ));
        }

        if (!$user) {
            return response()->json(array(
                'user_not_found' => true,
            ));
        }
        $hash = request('hash');
        $client = new Client();
        $otp = mt_rand(1000, 9999);
//        $content = "<#> Your OTP is: $otp $hash";
//        $content = urlencode($content);

        $user->update([
            'otp' => $otp
        ]);

        $this->sendSmsGuzzleRequest($new_phone_number, "Your pin is: {$otp}", new Client());

        return response()->json(array(
            'otp' => $otp
        ));
    }

    public function changeNumberGetOtp(Request $request)
    {
        $user = User::where('phone_number', '=', request('old_phone_number'))->first();

        $verified = $user->otp == request('entered_otp');

        if ($verified) {
            $user->update([
                'phone_number' => request('new_phone_number')
            ]);
        }

        return response()->json(array(
            'otp' => $user->otp,
            'verified' => $verified,
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userid = Str::uuid();
        User::forceCreate(
            ['user_id' => $userid] +
            $request->all());

        $user = User::where('user_id', $userid)->first();

        return response()->json($user);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        $updated_user = User::where('user_id', $user->user_id)->first();

        return response()->json($updated_user);
    }

    public function fetchConsumerHomeData(Request $request)
    {
        $banners = Banner::All();
        $products = Product::all();

        return Response::json(array(
            'banners' => $banners,
            'products' => $products
        ));
    }

    public function fetchSellerHomeData(Request $request)
    {
        $banners = Banner::All();
        $products = Product::all();

        return Response::json(array(
            'banners' => $banners,
            'products' => $products
        ));
    }

    public function fetchAgentHomeData(Request $request)
    {
        $banners = Banner::All();
        $products = Product::all();

        return Response::json(array(
            'banners' => $banners,
            'products' => $products
        ));
    }

    public function fetchWholesalerHomeData(Request $request)
    {
        $banners = Banner::All();
        $products = Product::all();

        return Response::json(array(
            'banners' => $banners,
            'products' => $products
        ));
    }

    public function consumerChatData(Request $request)
    {
        $chats = Chat::where('consumer_id', request('consumer_id'))
            ->where('seller_id', request('seller_id'))
            ->where('id', '>', request('id'))
            ->get();


        return Response::json(array(
            'chats' => $chats,
            'seller' => Seller::find(request('seller_id'))
        ));
    }

    public function sellerChatDataWithConsumer(Request $request)
    {
        $chats = Chat::where('consumer_id', request('consumer_id'))
            ->where('seller_id', request('seller_id'))
            ->where('id', '>', request('id'))
            ->get();


        return Response::json(array(
            'chats' => $chats,
            'consumer' => Consumer::find(request('consumer_id'))
        ));
    }

    public function sellerChatDataWithAgent(Request $request)
    {
        $chats = Chat::where('agent_id', request('agent_id'))
            ->where('seller_id', request('seller_id'))
            ->where('id', '>', request('id'))
            ->get();


        return Response::json(array(
            'chats' => $chats,
            'agent' => Agent::find(request('agent_id'))
        ));
    }

    public function agentChatDataWithWholesaler(Request $request)
    {
        $chats = Chat::where('wholesaler_id', request('wholesaler_id'))
            ->where('agent_id', request('agent_id'))
            ->where('id', '>', request('id'))
            ->get();


        return Response::json(array(
            'chats' => $chats,
            'wholesaler' => Agent::find(request('wholesaler_id'))
        ));
    }

    public function agentChatDataWithSeller(Request $request)
    {
        $chats = Chat::where('seller_id', request('seller_id'))
            ->where('agent_id', request('agent_id'))
            ->where('id', '>', request('id'))
            ->get();


        return Response::json(array(
            'chats' => $chats,
            'seller' => Seller::find(request('seller_id'))
        ));
    }

    public function wholesalerChatDataWithAgent(Request $request)
    {
        $chats = Chat::where('wholesaler_id', request('wholesaler_id'))
            ->where('agent_id', request('agent_id'))
            ->where('id', '>', request('id'))
            ->get();


        return Response::json(array(
            'chats' => $chats,
            'agent' => Agent::find(request('agent_id'))
        ));
    }

    public function wholesalerChatDataWithEkumfi(Request $request)
    {
        $chats = Chat::where('wholesaler_id', request('wholesaler_id'))
            ->where('ekumfi_info_id', request('ekumfi_info_id'))
            ->where('id', '>', request('id'))
            ->get();


        return Response::json(array(
            'chats' => $chats,
            'ekumfi_info' => EkumfiInfo::find(request('ekumfi_info_id'))
        ));
    }

    public function ekumfiChatData(Request $request)
    {
        $chats = Chat::where('ekumfi_info_id', request('ekumfi_info_id'))
            ->where('wholesaler_id', request('wholesaler_id'))
            ->where('id', '>', request('id'))
            ->get();


        return Response::json(array(
            'chats' => $chats,
            'wholesaler' => Wholesaler::find(request('wholesaler_id'))
        ));
    }

    public function sellerOrderId(Request $request)
    {
        $chat = Chat::where('seller_id', request("seller_id"))
            ->where('consumer_id', request("consumer_id") == null ? "" : request("consumer_id"))
            ->latest('created_at')
            ->first();

        if ($request->has('order_id')) {
            $order_id = "Order id: " . request('order_id');
            if ($chat == null || $chat->text != $order_id) {
                Chat::forceCreate([
                    'chat_id' => request('chat_id'),
                    'tag' => "order_id",
                    'consumer_id' => request("consumer_id") == null ? "" : request("consumer_id"),
                    'seller_id' => request('seller_id'),
                    'agent_id' => "",
                    'wholesaler_id' => "",
                    'ekumfi_info_id' => "",
                    'text' => $order_id,
                    'sent_by_consumer' => 1,
                    'sender_role' => "CONSUMER"
                ]);
            }
        }

        $chats = Chat::where('consumer_id', request('consumer_id'))
            ->where('seller_id', request('seller_id'))
            ->where('id', '>', request('id'))
            ->get();


        return Response::json(array(
            'chats' => $chats,
            'seller' => Seller::find(request('seller_id'))
        ));
    }

    public function groupCall(Request $request)
    {
        // Call consumer and seller into conference call
        $phone1 = substr(request("phone_number"), 1);
        $phone2 = '';
        if ($request->has('consumer_id')) {
            $phone2 = substr(Consumer::find(request("consumer_id"))->primary_contact, 1);
        } else if ($request->has('seller_id')) {
            $phone2 = substr(Seller::find(request("seller_id"))->primary_contact, 1);
        } else if ($request->has('agent_id')) {
            $phone2 = substr(Agent::find(request("agent_id"))->primary_contact, 1);
        } else if ($request->has('wholesaler_id')) {
            $phone2 = substr(Wholesaler::find(request("wholesaler_id"))->primary_contact, 1);
        }


        $client = new Client();
        $data = [
            'type' => 'bulk',
            'room_number' => '20000000',
            'session_file_name' => 'test_call',
            'participant' => "{$phone1},{$phone2}",
        ];

        try {
            $response = $client->request('POST', env("CALL_API_URL"), [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
        } catch (RequestException $e) {
            // Handle exceptions here
        }


        return Response::json(array(
            'success' => true,
            'curl_response' => $response,
        ));
    }

    public function pickupAddressAndNearbyCars(Request $request)
    {
        $long = request("long");
        $lat = request("lat");
        $service_category = request("service_category");

        $curl = curl_init();
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

        $decoded_response = json_decode($response);

        if ($decoded_response != null && $decoded_response->found) {
            $street_address = $decoded_response->data->Table[0]->Street;
        } else {
            $street_address = "Unknown Location";
        }

        $nearby_locations = DB::select('SELECT latitude, longitude FROM (SELECT *, (((ACOS(SIN(( ? * PI() / 180))*SIN(( latitude * PI() / 180)) + COS(( ? * PI() /180 ))*COS(( longitude * PI() / 180)) * COS((( ? - longitude) * PI()/180)))) * 180/PI()) * 60 * 1.1515 * 1.609344)AS distance FROM sellers) sellers INNER JOIN services ON services.seller_id=sellers.seller_id WHERE distance <= 25000 AND services.service_category = ? LIMIT 10', [$long, $long, $lat, $service_category]);

        return Response::json(array(
            'street_address' => $street_address,
            'nearby_locations' => $nearby_locations,
        ));
    }

    public function nearestRider(Request $request)
    {
        $long = request("long");
        $lat = request("lat");
        $service_category = request("service_category");

        $riders = DB::select('SELECT * FROM (SELECT *, ( (( ACOS(SIN(( ? * PI() / 180))*SIN(( latitude * PI() / 180)) + COS(( ? * PI() /180 ))*COS(( longitude * PI() / 180)) * COS((( ? - longitude) * PI()/180)))) * 180/PI()) * 60 * 1.1515 * 1.609344)AS distance FROM sellers) sellers INNER JOIN services ON services.seller_id=sellers.seller_id WHERE distance <= 25000  AND services.service_category = ? ORDER  BY distance LIMIT 1', [$long, $long, $lat, $service_category]);

        $curl = curl_init();
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

        $decoded_response = json_decode($response);

        if ($decoded_response != null && $decoded_response->found) {
            $street_address = $decoded_response->data->Table[0]->Street;
        } else {
            $street_address = "Unknown Location";
        }

        return Response::json(array(
            'riders' => $riders,
            'street_address' => $street_address
        ));
    }

    public function updateConfirmationToken(Request $request, User $user)
    {
        $user->update($request->all());
    }

    /**
     * @param string $phone_number
     * @param string $sms_message
     * @param Client $client
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSmsGuzzleRequest(string $phone_number, string $sms_message, Client $client)
    {
        $token = env("SMS_API_TOKEN");

        $response = $client->post(env("SMS_API_URL"), [
            'headers' => [
                'Authorization' => 'Basic ' . $token,
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'msisdn' => $phone_number,
                'message' => $sms_message,
                'senderId' => 'Ekumfi',
            ],
            'verify' => false,
        ]);
        /*Log::info('$response', [
            $response->getStatusCode()
        ]);*/
        return $response->getBody()->getContents();
    }
}
