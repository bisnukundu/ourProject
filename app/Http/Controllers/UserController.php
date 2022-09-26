<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Str;

class UserController extends Controller
{
    function userRegister(Request $request)
    {

        $validated_data =  $request->validate([
            "full_name" => 'required',
            "email" => 'required|unique:users',
            "user_name" => 'unique:users',
            'phone' => 'required',
            "password" => 'required|min:6|confirmed',
            "sponserId" => 'required',
        ]);

        $newUser = new User();
        $newUser->full_name = $request->full_name;
        $username = explode(" ", $request->full_name);

        $genarateName = '';
        if (count($username) > 0) {
            $genarateName .= $username[0];
        }
        if (count($username) > 1) {
            $genarateName .= $username[1];
        }

        $newUser->user_name = strtolower($genarateName . random_int(0, 999));
        $newUser->email = $request->email;
        $newUser->phone = $request->phone;
        $newUser->sponserId = $request->sponserId;
        $newUser->password = Hash::make($request->password);
        $newUser->save();
        return response()->json([
            "status" => 'pass',
            "message" => "Login Successfully",
            "data" => $newUser,
        ]);
    }

    function userLogin(Request $request)
    {
        $request->validate([
            "user_name" => "required",
            "password" => "required"
        ]);
        $user = User::where('user_name', $request->user_name)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('userLogin');
            return $token;
        } else {
            return response()->json([
                "status" => "faild",
                "message" => "Username or Password wrong..."
            ]);
        }
    }

    function userLogout()
    {
        $tokenId = Str::before(request()->bearerToken(), '|');

        Auth::user()->tokens()->where('id', $tokenId)->delete();

        return response()->json([
            "status" => "success",
            "message" => Auth::id() . "Logout Successfully..."
        ]);
    }
}
