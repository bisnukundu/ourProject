<?php

namespace App\Http\Controllers;

use App\Http\Helper\CustomHelper;
use App\Models\Admin;
use App\Models\BalanceHistory;
use App\Models\User;
use Auth;
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
                $user->active_balance = $user->active_balance - 250;
                $user->income_balance = $user->income_balance - 250;
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

    function adminSendBalance(Request $request)
    {
        $request->validate([
            'balance' => 'required|integer',
            'id' => 'required',
            'balance_type' => 'required'
        ]);
        $user = User::find($request->id);

        if ($request->balance_type == "active") {
            $user->active_balance = $user->active_balance + $request->balance;
            $user->save();
            return $this->returnResponse([$user]);
        }
        if ($request->balance_type == "income") {
            $user->income_balance = $user->income_balance + $request->balance;
            $user->save();
            return $this->returnResponse([$user]);
        }
    }


    function sendBalance(Request $request)
    {
        $request->validate([
            'balance' => 'required|integer',
            'id' => 'required',
            'balance_type' => 'required'
        ]);
        $current_user = User::find(Auth::user()->id);

        $user = User::find($request->id);
        if ($request->balance_type == "active") {
            if ($current_user->id != $request->id && $user && $current_user->active_balance >= $request->balance && $current_user->active_balance > 0 && $request->balance > 0) {
                $user->active_balance = $user->active_balance + $request->balance;
                $user->save();
                $current_user->active_balance = $current_user->active_balance - $request->balance;
                $current_user->save();
                BalanceHistory::create([
                    "from_user_name" => $current_user->user_name,
                    "to_user_name" => $user->user_name,
                    "amount" => $request->balance,
                    "status" => "active"
                ]);
                return $this->returnResponse([$user]);
            } else {
                return response()->json(
                    [
                        'status' => "faild",
                        'message' => "আপনার কাছে পর্যাপ্ত পরিমান টাকা নেই"
                    ]
                );
            }
        }
        if ($request->balance_type == "income") {
            if ($current_user->id != $request->id && $user && $current_user->income_balance >= $request->balance && $current_user->income_balance > 0 && $request->balance > 0) {
                $user->income_balance = $user->income_balance + $request->balance;
                $user->save();
                $current_user->income_balance = $current_user->income_balance - $request->balance;
                $current_user->save();
                BalanceHistory::create([
                    "from_user_name" => $current_user->user_name,
                    "to_user_name" => $user->user_name,
                    "amount" => $request->balance,
                    "status" => "income"
                ]);
                return $this->returnResponse([$user]);
            } else {
                return response()->json(
                    [
                        'status' => "faild",
                        'message' => "আপনার কাছে পর্যাপ্ত পরিমান টাকা নেই"
                    ]
                );
            }
        }
    }

    function balanceHistory()
    {
        $current_user = Auth::user();
        $history =  BalanceHistory::orderBy("created_at", "DESC")->where('from_user_name', $current_user->user_name)->orWhere('to_user_name', $current_user->user_name)->paginate(10);

        return $this->returnResponse($history);
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
