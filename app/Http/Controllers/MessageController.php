<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $messages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required',
        ]);

        $user = $request->user();
        $message = new Message();
        $message->sender_id = $user->id;
        $message->receiver_id = $validatedData['receiver_id'];
        $message->message = $validatedData['message'];
        $message->save();

        return response()->json(['message' => 'Message sent.']);
    }
}
