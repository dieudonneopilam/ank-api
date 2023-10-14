<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $userValidate = $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);
        $user = User::where('email', $userValidate['email'])->first();
        if(!$user) return response(["message" => "aucun utilisateur trouvé avec ce email"], 401);
        if(!Hash::check($userValidate['password'], $user->password)) return response(['message' => 'Aucun utilisateur trouvé avec ces identifiants'], 401);
        $token = $user->createToken("CLE_SECRETE")->plainTextToken;
        return response([
            'user' => $user,
            'token' => $token
        ],200);
    }
    public function signup(Request $request){
        $userValidate = $request->validate([
            'email' => ['required','unique:users,email'],
            'password' => ['required'],
            'firstName' => ['required'],
            'lastName' => ['required'],
            'role' => ['required']
        ]);
        $user = User::create([
            'email' => $userValidate['email'],
            'first_name' => $userValidate['firstName'],
            'last_name' => $userValidate['lastName'],
            'password' => bcrypt($userValidate['password']),
            'role' => $userValidate['role'],
        ]);
        return response($user,201);
    }
    public function logout(){
         auth()->user()->tokens->each(function($token, $key){
            $token->delete();
        });
        return response(['message' => 'Deconnexion'], 200);
    }
}
