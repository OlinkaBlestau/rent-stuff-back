<?php

namespace App\Http\Controllers\Api;

use App\Constants\UserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterFormRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterFormRequest $request): JsonResponse
    {
        $user = User::create(array_merge(
            $request->except('password'),
            ['password' => bcrypt($request->password)],
        ));

        return new JsonResponse([
            'message' => 'You were successfully registered. Use your email and password to sign in.'
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $user = $request->all();
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'You cannot sign with those credentials',
                'errors' => 'Unauthorised'
            ], 401);
        }

        if (Auth::user()->role === UserRoles::LANDLORD->value) {
            $token = Auth::user()->createToken(config('app.name'), ['landlord']);
        } else {
            $token = Auth::user()->createToken(config('app.name'), ['renting']);
        }
        $token->token->expires_at = Carbon::now()->addDay();
        $token->token->save();

        $user = Auth::user();

        return response()->json([
            'userId' => Auth::id(),
            'token_type' => 'Bearer',
            'role' => $user->role,
            'token' => $token->accessToken,
            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'You are successfully logged out',
        ]);
    }
}
