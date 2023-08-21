<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;

class MessageController extends Controller
{

    //create a new message
    public function index(Request $request)
    {
        try {

            $user = $request->user();

            // $validatedData = $request->validate([
            //     'receiver_id' => 'required|exists:users,id',
            //     'sender_id' => 'required',
            // ]);

            $message = Message::create([
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'message' => $request->message
            ]);
            // broadcast(new MessageSent($message))->toOthers();

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
    public function getMessages(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'receiver_id' => 'required',
                'sender_id' => 'required',
            ]);

            $senderId = $validatedData['sender_id'];
            $receiverId = $validatedData['receiver_id'];

            $messages = Message::Where(function ($query) use ($senderId, $receiverId){
                $query->where('sender_id', $senderId)
                ->where('receiver_id', $receiverId);
            })->orWhere(function ($query) use ($senderId, $receiverId){
                $query->where('sender_id', $receiverId)
                ->where('receiver_id', $senderId);
            })->orderBy('created_at', 'asc')->get();

            if($messages){
                return response()->json(['messages' => $messages], 200);
            }else{
                return response()->json([
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
