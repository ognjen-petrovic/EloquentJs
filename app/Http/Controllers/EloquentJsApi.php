<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EloquentJsApi extends Controller
{
    public function index(Request $request)
    {
        $payload = json_decode($request->input('payload'), true);

        $ret = call_user_func_array('\\App\\' . $payload[0]['method'], $payload[0]['params']);
        for($i = 1; $i < count($payload); ++$i)
        {
            $ret = call_user_func_array([$ret, $payload[$i]['method']], $payload[$i]['params']);
        }

        return $ret;
    }
}
