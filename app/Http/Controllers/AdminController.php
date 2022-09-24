<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Hash;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function adminRegister(Request $request)
    {

        $validated_data =  $request->validate([
            "full_name" => 'required',
            "user_name" => 'unique:admins',
            "password" => 'required|min:6|confirmed',

        ]);

        $newadmin = new Admin();
        $newadmin->full_name = $request->full_name;
        $adminname = explode(" ", $request->full_name);
        $newadmin->user_name = strtolower($adminname[0] . $adminname[1]);
        $newadmin->password = Hash::make($request->password);
        $newadmin->save();
        return response()->json([
            "status" => 'pass',
            "message" => "Login Successfully",
            "data" => $newadmin,
        ]);
    }

    function adminLogin(Request $request)
    {
        $request->validate([
            "user_name" => "required",
            "password" => "required"
        ]);
        $admin = Admin::where('user_name', $request->user_name)->firstOrFail();
        if ($admin && Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('adminLogin');
            return $token;
        } else {
            return response()->json([
                "status" => "faild",
                "message" => "Username or Password wrong..."
            ]);
        }
    }
}
