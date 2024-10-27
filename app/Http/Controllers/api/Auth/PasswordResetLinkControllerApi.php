<?php

namespace App\Http\Controllers\api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkControllerApi extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);
        
        $code = rand(1000, 9999);

        User::where('email', $request->email)->update([
            "password_reset_code" => $code,
            "password_reset_expire" => Carbon::now()->addMinutes(2)->format('Y-m-d H:i:s')
        ]);

        // Mail::send();

        return response()->json(['status' => 'success']);
    }
}
