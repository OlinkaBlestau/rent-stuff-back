<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends Controller
{

    public function store(Request $request)
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
}
