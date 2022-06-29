<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThingRequest;
use App\Models\Category;
use App\Models\Thing;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThingController extends Controller
{

    public function create(ThingRequest $request): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $data = $request->all();

        if (isset($data['photo'])) {
            [$imageName, $imageContent] = explode('\\', $data['photo']);
            $data['photo'] = $imageName;
        }
        $thing = Thing::create($data);

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
        $thing->shop()->associate($data['shop_id']);
        $thing->category()->associate($data['category_id']);
        $thing->save();

        return response('Ok');
    }

    public function showByUser($id)
    {
        return response(User::with('shop.thing.category')->findOrFail($id));
    }

    public function showAll()
    {
        return response(Thing::with('category')->get());
    }

    public function show($id)
    {
        return response(Thing::with('category','shop')->findOrFail($id));
    }

    public function update(ThingRequest $request, $id): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $current = Thing::FindOrFail($id);

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

        $current->fill($data)->save();

        return response(true);
    }


    public function delete(int $id): \Illuminate\Http\Response
    {
        Thing::destroy($id);
        return response(true);
    }
}
