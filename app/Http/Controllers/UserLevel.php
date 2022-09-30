<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class UserLevel extends Controller
{
    function getLevel()
    {
        $user =  Auth::user();
        $level = [
            'level_1' => 0,
            'level_2' => [],
            'level_3' => 0,
            'level_4' => 0,
            'level_5' => 0,
            'level_6' => 0,
            'level_7' => 0,
        ];

        $test = [
            "rahatislam943",
            "noyonislam775",
            "marufislam181",
            "sayadislam922"
        ];

        $level_1 = User::where('sponserId', $user->user_name)->where('status', 0)->pluck("user_name");

        $level_2 = User::whereIn('sponserId', $level_1)->where('status', 0)->pluck("user_name");

        $level_3 = User::whereIn('sponserId', $level_2)->where('status', 0)->pluck("user_name");

        $level_4 = User::whereIn('sponserId', $level_3)->where('status', 0)->pluck("user_name");

        $level_5 = User::whereIn('sponserId', $level_4)->where('status', 0)->pluck("user_name");

        $level_6 = User::whereIn('sponserId', $level_5)->where('status', 0)->pluck("user_name");

        $level_7 = User::whereIn('sponserId', $level_6)->where('status', 0)->pluck("user_name");



        $level['level_1'] = $level_1;
        $level['level_2'] = $level_2;
        $level['level_3'] = $level_3;
        $level['level_4'] = $level_4;

        $level['level_5'] = $level_5;
        $level['level_6'] = $level_6;
        $level['level_7'] = $level_7;





        return $level;
    }
}
