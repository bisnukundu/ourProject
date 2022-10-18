<?php

namespace App\Http\Controllers;

use App\Http\Helper\CustomHelper;
use App\Models\Admin;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use CustomHelper;

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
        $admin = Admin::where('user_name', $request->user_name)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('adminLogin');
            return [$token, $admin];
        } else {
            return response()->json([
                "status" => "faild",
                "message" => "Username or Password wrong..."
            ]);
        }
    }

    function getAllUser($username = '')
    {
        $users = User::orderBy("created_at", "DESC")->where('user_name', 'LIKE', "%" . $username . "%")->paginate(10);
        return $this->returnResponse($users);
    }

    function activeUser($id)
    {
        $user = User::find($id);

        if ($user->active_balance >= 250) {
            $total = $user->active_balance + $user->income_balance;
            if ($total >= 500) {
                $user->status = 1;
                $user->save();
                return $this->returnResponse([$user]);
            } else {
                return response()->json([
                    "status" => "faild",
                    "message" => "আপনার পর্যাপ্ত পরিমান ব্যালেন্স নেই, অনুগ্রহ করে এডমিনের সাথে যোগাযোগ করুন।"
                ]);
            }
        } else {
            return response()->json([
                "status" => "faild",
                "message" => "আপনার পর্যাপ্ত পরিমান ব্যালেন্স নেই, অনুগ্রহ করে এডমিনের সাথে যোগাযোগ করুন।"
            ]);
        }
    }

    function sendActiveBalance($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->active_balance = 250;
            $user->save();
            return $this->returnResponse([$user]);
        };
    }

    function deactiveUser($id)
    {
        $user = User::find($id);
        $user->status = 0;
        $user->save();
        return $this->returnResponse([$user]);
    }

    function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return "Delete Successfuly";
    }
}
