<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Instructor;
use App\Student;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;


class ChatController extends Controller
{
    use UploadTrait;

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
                return Chat::all();
            case 'student':
                $instructorcourses = $user->info->instructorCourses;
                $chats = [];
                $instructor_course_chat_arrays = [];
                foreach ($instructorcourses as $instructorcourse) {
                    $instructor_course_chat_arrays[] = $instructorcourse->chats;
                }
                foreach ($instructor_course_chat_arrays as $instructor_course_chat_array) {
                    foreach ($instructor_course_chat_array as $instructor_course_chat) {
                        $chats[] = $instructor_course_chat;
                    }
                }
                return $chats;
            default:
                'default';
                break;
        }
    }

    public function scopedChats(Request $request)
    {
        $chats = Chat::where('consumer_id', request('consumer_id'))
            ->where('seller_id', request('seller_id'))
            ->where('id', '>', request('id'))
            ->get();

        return Response::json(array(
            'chats' => $chats
        ));
    }

    public function scopedLatestChats(Request $request)
    {
        if ($request->has('consumer_id')) {
            return DB::table('chats')
                ->whereRaw('(id in (select max(id) from chats group by (seller_id))) AND consumer_id = ?', [request('consumer_id')])
                ->get();
        }
        return DB::table('chats')
            ->whereRaw('(id in (select max(id) from chats group by (consumer_id))) AND seller_id = ?', [request('seller_id')])
            ->get();
    }

    public function scopedLatestEkumfiChats(Request $request)
    {
        return DB::table('chats')
            ->whereRaw('(id in (select max(id) from chats group by (seller_id))) AND consumer_id = ?', [''])
            ->get();
    }

    public function scopedLatestAgentChats(Request $request)
    {
        return DB::table('chats')
            ->whereRaw('(id in (select max(id) from chats group by (seller_id))) AND agent_id = ?', [request('agent_id')])
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chat = Chat::find(request('chat_id'));
        if (!$chat) {
            $attributes = [
                'chat_id' => request('chat_id'),
                'chat_ref_id' => request('chat_ref_id'),
                'consumer_id' => request('consumer_id') == null ? "" : request('consumer_id'),
                'seller_id' => request('seller_id'),
                'agent_id' => request('agent_id'),
                'wholesaler_id' => request('wholesaler_id'),
                'ekumfi_info_id' => request('ekumfi_info_id'),
                'sent_by_consumer' => request('sent_by_consumer'),
                'sender_role' => request('sender_role')
            ];
            if ($request->has('file')) {

                // Get image file
                $image = $request->file('file');// Make a file name based on attachmenttitle and current timestamp
                $title = $request->input('chat_id');// Define folder path
                $folder = '/uploads/chats/';// Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $title . '.' . $image->getClientOriginalExtension();// Upload image
                $this->uploadOne($image, $folder, '', $title);

                Log::info('debit_response', [
                    'generated_path' => asset('storage/app')
                ]);
                $attributes = $attributes +
                    [
                        'attachment_url' =>  asset('storage/app') . "$filePath",
                        'attachment_type' => request('attachment_type'),
                        'attachment_title' => request('attachment_title')
                    ];
            } else {
                if ($request->has('attachment_url')) {
                    $attributes = $attributes +
                        [
                            'attachment_url' => request('attachment_url'),
                            'attachment_type' => request('attachment_type'),
                            'attachment_title' => request('attachment_title')
                        ];
                }
                $attributes = $attributes +
                    [
                        'text' => request('text'),
                        'link' => request('link'),
                        'link_title' => request('link_title'),
                        'link_description' => request('link_description'),
                        'link_image' => request('link_image')
                    ];
            }
            $chat = Chat::forceCreate($attributes);
            if ($request->has('chat_ref_id')) {
                $referenced_chat = Chat::find(request('chat_ref_id'));
                return Response::json(array(
                    'chat' => Chat::where('chat_id', $chat->chat_id)->first(),
                    'referenced_chat' => Chat::where('chat_id', $referenced_chat->chat_id)->first()
                ));
            } else {
                return Response::json(array(
                    'chat' => Chat::where('chat_id', $chat->chat_id)->first()
                ));
            }
        } else {
            return Response::json(array(
                'already_exists' => true,
                'chat' => Chat::where('chat_id', $chat->chat_id)->first()
            ));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        return $chat;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Chat $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        $chat->update($request->all());

        $updated_chat = Chat::where('chat_id', $chat->chat_id)->first();

        return response()->json($updated_chat);
    }
}
