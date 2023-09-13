<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;
use Ramsey\Uuid\Uuid;

class MessageController extends Controller
{

    //create a new message
    public function index(Request $request)
    {
        try {
            $senderId = $request->senderId;
            $receiverId = $request->receiverId;

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
                    'success' => true,
                    'message' => 'Message sent successfully'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
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
    
            $otherUserIds = $messages->flatMap(function ($message) use ($userId) {
                // Concatenate sender_id and receiver_id into a single collection
                return [$message->sender_id, $message->receiver_id];
            })->unique()->reject(function ($otherUserId) use ($userId) {
                return $otherUserId == $userId;
            });
    
            // Fetch other user information
            $users = User::whereIn('userId', $otherUserIds)->get();
    
            // Associate each message with its user information
            $messagesWithUsers = $messages->map(function ($message) use ($users, $userId) {
                // Determine whether the user is the sender or receiver
                $otherUserId = $message->sender_id !== $userId ? $message->sender_id : $message->receiver_id;
    
                // Find the user information for this message
                $user = $users->firstWhere('userId', $otherUserId);
    
                // Add the user information to the message
                $message->user = $user;
    
                return $message;
            });
    
            if ($messagesWithUsers) {
                return response()->json([
                    'success' => true,
                    'messages' => $messagesWithUsers,
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

    public function getChatId(Request $request)
    {
        try {
            $senderId = $request->senderId;
            $receiverId = $request->receiverId;

            // Check if a conversation between the sender and receiver exists
            $chat = Message::where(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId);
            })->orWhere(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId);
            })->first();

            if ($chat) {
                return response()->json([
                    'status' => true,
                    'chat_id' => $chat->chat_id
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'chat_id' => 'Chat not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getReceiverId(Request $request)
    {
        try {
            $userId = $request->userId;
            $chatId = $request->chatId;

            // Find the message associated with the given chat ID
            $message = Message::where('chat_id', $chatId)->first();

            if (!$message) {
                return response()->json([
                    'status' => false,
                    'message' => 'Message not found for the given chat ID'
                ], 404);
            }

            // Determine if the user is the sender or receiver in this chat
            if ($message->sender_id === $userId) {
                $receiverId = $message->receiver_id;
            } elseif ($message->receiver_id === $userId) {
                $receiverId = $message->sender_id;
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User is not a participant in this chat'
                ], 403);
            }

            return response()->json([
                'status' => true,
                'receiver_id' => $receiverId
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
