<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userValidate = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'lastName' => ['required'],
            'firstName' => ['required'],
            'role' => ['required'],
            'password' => ['required'],
            'user_id' => ['required', 'numeric']
        ]);
        if ((User::where('id', $userValidate['user_id']))->first()->role != "admin") return response(['message' => 'action refused'], 403);
        User::create([
            'email' => $userValidate['email'],
            'first_name' => $userValidate['firstName'],
            'last_name' => $userValidate['lastName'],
            'role' => $userValidate['role'],
            'password' => bcrypt($userValidate['password'])
        ]);
        return response(['message' => 'user added'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request ,$id)
    {
        $userValidate = $request->validate([
            'user_id' => ['required', 'numeric']
        ]);
        if ((User::where('id', $userValidate['user_id']))->first()->role != "admin") return response(['message' => 'action refused'], 403);
        $user = User::where('id', $id)->first();
        if (!$user) return response(['message' => "aucun user touve avec cet id $id"], 403);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $userValidate = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'lastName' => ['required'],
            'firstName' => ['required'],
            'role' => ['required'],
            'password' => ['required'],
            'user_id' => ['required', 'numeric']
        ]);
        $user = User::where('id', $id)->first();
        $userVal = User::where('id', $userValidate['user_id'])->first();
        if ($userVal->role != "admin") return response(['message' => 'action refused'], 403);
        if (!$user) return response(['message' => "aucun user touve avec cet id $id"], 403);
        $user->update([
            'email' => $userValidate['email'],
            'last_name' => $userValidate['lastName'],
            'first_name' => $userValidate['firstName'],
            'role' => $userValidate['role'],
            'password' => $userValidate['password'],
        ]);
        return response(['message' => 'user updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request ,$id)
    {
        $userValidate = $request->validate([
            'user_id' => ['required', 'numeric'],
        ]);
        $user = User::where('id',$id)->first();
        $userVal = User::where('id', $userValidate['user_id'])->first();
        if(!$user) return response(['message' => "aucun user trouvé avec cet id $id"], 403);
        if($userVal->role != "admin") return response(['message' => 'action refuse'], 404);
        $value = User::destroy($id);
        if (boolval($value) == false) {
            return response(['message' => "aucun user trouve avec cet id $id"], 403);
        }
        return response(['message' => "suppression effectuee avec sucess"], 200);
    }
}
