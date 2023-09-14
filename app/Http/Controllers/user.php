<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use App\Models\WaitingList;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class user extends Controller
{

    public function login(Request $request)
    {

        try {

            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $email = $request['email'];
            $user = ModelsUser::where('email', $email)->firstOrFail();
           

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {

                if (!$user->email_verified_at) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Email not verified, Check your email inbox to verify',
                    ], 200);
                }

                $token = $user->createToken('Authentication Token')->plainTextToken;

                return response(
                    [
                        'success' => true,
                        'message' => 'user Logged in successfully',
                        'user' => $user,
                        'token' => $token
                    ],
                    200
                );
            } else {
                return response(
                    [
                        'success' => false,
                        'message' => 'Login failed'
                    ],
                    201

                );
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = new ModelsUser();

            $user->name = $request->name;
            $user->userId = Str::uuid()->toString();
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            $res = $user->save();

            // Send verification email
            $user->sendEmailVerificationNotification();

            if ($res) {
                return response(
                    [
                        'success' => true,
                        'message' => 'user Registered successfully, check email to activate account',
                        'user' => $user
                    ],
                    200
                );
            } else {
                return response(
                    [
                        'success' => false,
                        'message' => 'failed to register user'
                    ],
                    201
                );
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function logout(Request $request)
    {

        try {
            $token = $request->user()->tokens();
            $res = $token->delete();

            if ($res) {
                return response([
                    'success' => true,
                    'message' => 'logged out'
                ], 200);
            } else {
                return response([
                    'success' => false,
                    'message' => 'logout failed'
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteUser(Request $request)
    {
        try {

            $user = $request->user();

            $res = $user->delete();
            if ($res) {
                return response([
                    'success' => true,
                    'message' => 'user deleted successfully'
                ], 200);
            } else {
                return response([
                    'success' => false,
                    'message' => 'user delete failed'
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getUser(Request $request)
    {

        try {

            $user = $request->user();

            $res = $user->get();
            if ($res) {
                return response([
                    'success' => true,
                    'message' => 'user obtained successfully',
                    'user' => $user
                ], 200);
            } else {
                return response([
                    'success' => false,
                    'message' => 'user obtain failed'
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function updateUser(Request $request)
    {
        try {

            $user = $request->user();

            $user->id;
            $user->userId;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->bio = $request->bio;
            $user->occupation = $request->occupation;
            $user->profile = $request->profile;

            // Check if a profile image was uploaded
            if ($request->hasFile('profile')) {
                // Delete the previous profile image if it exists
                if ($user->profile) {
                    Storage::delete($user->profile);
                }

                // Store the new profile image
                $path = $request->file('profile')->store('profile');
                $user->profile = $path;
            }

            $res = $user->save();

            if ($res) {
                return response([
                    'success' => true,
                    'message' => 'user updated Successfully'
                ], 200);
            } else {
                return response([
                    'success' => false,
                    'message' => 'user update Failed'
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function fetchProfile(Request $request)
    {
        try {
            $userId = $request->userId;

            $user = ModelsUser::where('userId', $userId)->first();

            if ($user) {
                return response()->json([
                    'status' => true,
                    'user' => $user
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function getUsers()
    {
        try {

            $user = ModelsUser::all();

            return response([
                'success' => true,
                'message' => 'user obtained successfully',
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function addEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:waiting_list,email',
            ]);

            $user = new WaitingList();

            $user->email = $request->email;

            $res = $user->save();

            if ($res) {
                return response(
                    [
                        'success' => true,
                        'message' => 'email added successfully',
                        'user' => $user
                    ],
                    200
                );
            } else {
                return response(
                    [
                        'success' => false,
                        'message' => 'failed to add email '
                    ],
                    201
                );
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = ModelsUser::findOrFail($id);
    
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }
    
        if (!$user->markEmailAsVerified()) {
            return response()->json(['message' => 'Email verification failed.']);
        }
    
        return response()->json(['message' => 'Email verified successfully.']);
    }

    public function resetPassword(Request $request){
        
    }
}
