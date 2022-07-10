<?php

namespace App\Http\Controllers;

use App\Jobs\SetThingCoords;
use Illuminate\Http\Request;

class IotController extends Controller
{
    public function setThingCoords(Request $request)
    {
        $data = $request->only(['latitude', 'longitude', 'thingId']);
        $this->dispatch(new SetThingCoords($data['latitude'], $data['longitude'], $data['thingId']));

        return response(true);
    }
}
