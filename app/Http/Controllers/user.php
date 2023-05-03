<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class user extends Controller
{
    //
    public function login(Request $request){

        try{

            $request ->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
    
            $email = $request['email'];
            $user =ModelsUser::where('email', $email)->firstOrFail();
            $token = $user->createToken('Authentication Token')->plainTextToken;
    
            $credentials = $request->only('email', 'password');
    
            if(Auth::attempt($credentials)){
                return response(
                    [
                        'success' =>true,
                        'message'=>'user Logged in successfully',
                        'user' =>$user,
                        'token' =>$token
                    ],200
                    );
            }else{
                return response(
                    [
                        'success' =>false,
                        'message' =>'Login failed'
                    ],201
                   
                    );
            }

        }catch (\Throwable $th) {
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
                'password' =>'required'
            ]);

            $user = new ModelsUser();

            $user->name = $request->name;
            $user ->userId = Str::uuid()->toString();
            $user ->email = $request->email;
            $user ->password = Hash::make($request->password);

            $res = $user -> save();

            if($res){
                return response(
                    [
                        'success'=>true,
                        'message'=>'user Registered successfully',
                        'user' => $user
                    ],200
                );
            }else{
                return response(
                    [
                        'success'=>false,
                        'message' =>'failed to register user'
                    ],201
                );
            }


        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function logout(Request $request){

        try{
            $token = $request->user()->tokens();
        $res = $token->delete();

        if($res){
            return response([
                'success' =>true,
                'message'=>'logged out'
            ],200);
        }else{
            return response([
                'success' =>false,
                'message' =>'logout failed'
            ],201);
        }

        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        
    }

    public function deleteUser($id){
        try{

            $user = ModelsUser::find($id);

            $res = $user->delete();
            if($res){
                return response([
                    'success' =>true,
                    'message'=>'user deleted successfully'
                ],200);
            }else{
                return response([
                    'success' =>false,
                    'message' =>'user delete failed'
                ],201);
            }



        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


  
}
