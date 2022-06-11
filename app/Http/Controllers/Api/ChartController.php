<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Thing;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{
    public function getThingsPerMonth()
    {
        $user = Auth::user();
        //$shops = Shop::where['us']

        $things = Thing::with([
            'shop' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
        ])->get();

        return response([
            'things_count' => $things,
        ]);
    }
}
