<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopRequest;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends Controller
{

    public function show($id)
    {
        return response()->json([
            Shop::with('thing')->findOrFail($id)
        ],
            Response::HTTP_OK
        );
    }

    public function store(ShopRequest $request)
    {
        $data = $request->all();
        Shop::create($data);

        return response()->json(
            [
                'created' => true
            ],
            Response::HTTP_CREATED
        );
    }

    public function isUserHasShop(User $user): \Illuminate\Http\Response
    {

        $shop = Shop::whereHas('user', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();

        return response([
            "has" => count($shop) > 0
        ]);
    }

    public function update(ShopRequest $request, $id): JsonResponse
    {
        $current = Shop::FindOrFail($id);
        $data = $request->all();
        $current->fill($data)->save();
        return response()->json(['updated' => true], Response::HTTP_OK);
    }
}
