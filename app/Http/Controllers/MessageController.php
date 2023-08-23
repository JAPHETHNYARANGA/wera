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

            // $user = $request->user();
            // $userId = auth()->user()->userId;

            // $validatedData = $request->validate([
            //     'receiver_id' => 'required|exists:users,id',
            //     'sender_id' => 'required',
            // ]);

            $message = Message::create([
                // 'sender_id' => $userId,
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
            // $userId = auth()->user()->userId;
            $senderId = $request->userId;
            $receiverId = $request->userId;

          

            $messages = Message::where('sender_id', $senderId)
                    ->orWhere('receiver_id', $receiverId)
                    ->orderBy('created_at', 'asc')
                    ->get();
            // $messages = Message::all();
            if($messages){
                return response()->json([
                    'success'=>true,
                    'messages' => $messages
                ], 200);
            }else{
                return response()->json([
                    'success'=>false,
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

    public function getSpecificMessage(){
        try{


        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
