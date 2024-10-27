<?php

namespace App\Http\Controllers\api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasswordControllerApi extends Controller
{
    public function store(Request $request)
    {
        $user = User::where('password_reset_code', $request->code)->first();

        if ($user == null) {
            return response()->json('wrong code');
        }

        if ($user->password_reset_expire < date("Y-m-d H:i:s")) {
            return response()->json('code expired');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'password_reset_code' => null,
            'password_reset_expire' => null
        ]);

        return response()->json('updated successfully');
    }
}
