<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;
use Ramsey\Uuid\Uuid;

class MessageController extends Controller
{

    //create a new message
    public function index(Request $request)
    {
        try {
            $senderId = $request->sender_id;
            $receiverId = $request->receiver_id;

            // Check if a conversation between the sender and receiver already exists
            $existingChat = Message::where(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId);
            })->orWhere(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId);
            })->first();

            if ($existingChat) {
                // Reuse the existing chat_id
                $chatId = $existingChat->chat_id;
            } else {
                // Generate a UUID for the chat ID
                $chatId = Uuid::uuid4()->toString();
            }

            $message = Message::create([
                'chat_id' => $chatId,
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'message' => $request->message
            ]);

            if ($message) {
                return response()->json([
                    'message' => 'Message sent successfully'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Message sent failed'
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    //fetch messages
    // public function getMessages(Request $request)
    // {
    //     try {
    //         // $userId = auth()->user()->userId;
    //         $senderId = $request->userId;
    //         $receiverId = $request->userId;



    //         $messages = Message::where('sender_id', $senderId)
    //                 ->orWhere('receiver_id', $receiverId)
    //                 ->orderBy('created_at', 'asc')
    //                 ->get();
    //         // $messages = Message::all();
    //         if($messages){
    //             return response()->json([
    //                 'success'=>true,
    //                 'messages' => $messages
    //             ], 200);
    //         }else{
    //             return response()->json([
    //                 'success'=>false,
    //                 'message' => 'Failure fetching messages'
    //             ], 201);
    //         }
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $th->getMessage()
    //         ], 500);
    //     }
    // }

    public function getMessages(Request $request)
    {
        try {
            $userId = $request->userId;

            // Subquery to get the latest message for each chat_id group
            $latestMessagesSubquery = Message::selectRaw('MAX(created_at) as latest_created_at')
                ->groupBy('chat_id');

            $messages = Message::whereIn(
                'created_at', // Use created_at as part of the subquery to filter by latest messages
                $latestMessagesSubquery
            )
                ->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->orWhere('receiver_id', $userId);
                })
                ->orderBy('created_at', 'desc') // Order by descending to get the latest messages first
                ->get();

            if ($messages) {
                return response()->json([
                    'success' => true,
                    'messages' => $messages
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failure fetching messages'
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }



    public function getSpecificMessage(Request $request)
    {
        try {
            $chatId = $request->chatId; // Use chatId instead of messageId

            $message = Message::where('chat_id', $chatId)->get();

            if ($message) {
                return response()->json([
                    'success' => true,
                    'messages' => $message
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failure fetching messages'
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
