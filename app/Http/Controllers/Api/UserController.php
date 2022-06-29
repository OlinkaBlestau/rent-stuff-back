<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function show($id)
    {
        return response(User::with('shop')->findOrFail($id));
    }

    public function update(UserUpdateRequest $request, $id): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $current = User::FindOrFail($id);

        $data = $request->only('name', 'surname', 'email', 'phone', 'photo');
        $password = $request->only(['password' => bcrypt($request->password)]);

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

        $current->fill($data)->save();
        $current->fill($password)->save();
        return response(true);
    }
}
