<?php

namespace App\Http\Controllers\Api;

use App\Constants\UserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        $data = $request->all();

        if (isset($data['photo'])) {
            [$imageName, $imageContent] = explode('\\', $data['photo']);
            $data['photo'] = $imageName;
        }

        if (isset($data['photo'])) {
            $imageContent = str_replace(
                [
                    'data:image/jpeg;base64,',
                    'data:image/png;base64,',
                    'data:image/jpg;base64,'
                ],
                '',
                $imageContent
            );
            $imageContent = str_replace(' ', '+', $imageContent);
            file_put_contents(storage_path() . '/app/public/images/' . $imageName, base64_decode($imageContent));
        }
        $user->photo = $imageName;
        $user->save();

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
