<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::query()->where("email", $request->input("email"))->first();

        if ($user == null) {
            return response()->json([
                "status" => false,
                "message" => "Email atau password salah",
                "data" => null
            ]);
        }

        if (!Hash::check($request->input("password"), $user->password)) {
            return response()->json([
                "status" => false,
                "message" => "Email atau password salah",
                "data" => null
            ]);
        }

        $token = $user->createToken("auth_token");

        return response()->json([
            "status" => true,
            "message" => "",
            "data" => [
                "auth" => [
                    "token" => $token->plainTextToken,
                    "token_type" => 'Bearer'
                ],
                "user" => $user
            ]
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            "status" => true,
            "message" => "Berhasil logout",
            "data" => ""
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors(),
                "data" => ""
            ]);
        }

        $auth = User::query()->where('id', Auth::user()->id)->first();
        $auth->fill($request->all());
        $auth->save();

        return response()->json([
            "status" => true,
            "message" => "Berhasil update password",
            "data" => ""
        ]);
    }
}
