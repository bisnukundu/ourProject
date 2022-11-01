<?php

namespace App\Http\Controllers;

use App\Http\Helper\CustomHelper;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Str;

class UserController extends Controller
{
    use CustomHelper;
    function userRegister(Request $request)
    {

        $validated_data =  $request->validate([
            "full_name" => 'required',
            "email" => 'required|unique:users',
            "user_name" => 'unique:users',
            'phone' => 'required',
            "password" => 'required|min:6|confirmed',
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
        $newUser->sponserId = $request->sponserId ?? '';
        $newUser->password = Hash::make($request->password);


        // we are checking sponserId is valid or not
        $refferlLinkValidate = User::where("user_name", $request->sponserId)->get();

        if (count($refferlLinkValidate) != 0 || $request->sponserId == 'bisnu' || $request->sponserId == '') {

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
        $user =  User::where("sponserId", $username)->orderBy("created_at", "DESC")->paginate(10);

        return $this->returnResponse($user);
    }

    function getUserById($id = null)
    {
        return User::where('id', $id ?? Auth::id())->get();
    }

    function getUserByName($username = null)
    {
        return User::where('user_name', $username)->get();
    }
}
