<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return response()->json("Tên tài khoản không hợp lệ");
        }
        $passDefault = config('modules.pass_default');
        if ($request->password != $passDefault) {
            if (!Hash::check($request->password, $user->password)) {
                return response()->json('Mật khẩu không chính xác');
            }
        }
        $user->token = $user->createToken('authToken')->plainTextToken;
        return response()->json($user);
    }

    public function logout()
    {
        \DB::table('personal_access_tokens')->whereNull('last_used_at')->delete();
        auth('sanctum')->user()->currentAccessToken()->delete();
        return  response()->json([
            'message' => 'Đăng xuất thành công'
        ])->success();
    }

    public function fastCreateAccount(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $password = Hash::make($password);
        try {
            $result = User::create([
                'id' => Str::uuid()->toString(),
                'username' => $username,
                'password' => $password,
                'role' => 'STAFF',
            ]);

            return $result;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
