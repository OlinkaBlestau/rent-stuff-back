<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function show($id)
    {
        return response()->json([
            User::findOrFail($id)
        ],
            Response::HTTP_OK
        );
    }

    public function update(Request $request, $id): JsonResponse
    {
        $current = User::FindOrFail($id);

        $data = $request->only('name', 'surname', 'email', 'phone');
        $password = $request->only(['password' => bcrypt($request->password)]);
        $current->fill($data)->save();
        $current->fill($password)->save();
        return response()->json(['updated' => true], Response::HTTP_OK);
    }
}
