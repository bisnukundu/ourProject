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
        $user_sponser_name = strtolower($genarateName . random_int(0, 999));

        $newUser->user_name = $user_sponser_name;
        $newUser->email = $request->email;
        $newUser->phone = $request->phone;
        $newUser->sponserId = $request->sponserId;
        $newUser->password = Hash::make($request->password);
        // This is for server 
        // $newUser->referral_link = env('APP_URL') . "/user/register/?sopnser=" . strtolower($genarateName . random_int(0, 999));
        // This is for local testing
        $newUser->referral_link = "http://127.0.0.1:5173/user/register/?sopnser=" . $user_sponser_name;

        // we are checking sponserId is valid or not
        $refferlLinkValidate = User::where("user_name", $request->sponserId)->get();

        if (count($refferlLinkValidate) != 0 || $request->sponserId == 'Bisnu') {

            $newUser->save();
            return response()->json([
                "status" => 'pass',
                "message" => "Register Successfully",
                "data" => $newUser,
            ]);
        } else {
            return response()->json([
                "status" => 'faild',
                "message" => "আপনার SponserID সঠিক নয়!",
            ]);
        }
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
            return [$token, $user];
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

    function referralFriend($username)
    {
        $user =  User::where("sponserId", $username)->orderBy("created_at","DESC")->paginate(10);

        if (count($user) > 0) {
            return response()->json([
                "status" => "pass",
                "message" => "Referral Friend get successfull",
                "data" => $user
            ]);
        } else {
            return response()->json([
                "status" => "faild",
                "message" => "Referral Friend get Faild",
            ]);
        }
    }
   
}
