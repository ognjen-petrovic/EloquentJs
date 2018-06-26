<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EloquentJsApi extends Controller
{
    public function index(Request $request)
    {
        $actions = [
            'all'      => 'view',
            'count'    => 'view',
            'paginate' => 'view',
            'where'    => 'view',
            'orWhere'  => 'view',
            'get'      => 'view',
            'with'     => 'view',
            'update'   => 'update',
            'orderBy'  => 'view',
            'find'  => 'view',
        ];

        if (is_null(\Auth::user()))
        {
            \Auth::login(new \App\User());
        }

        $payload = json_decode($request->input('payload'), true);
        $methods = $payload['methods'];
        $model = $payload['model'];

        foreach($methods as $m)
        {
           if ( array_key_exists($m['method'], $actions) == false || \Gate::allows($actions[$m['method']], \Auth::user()) == false)
           {
                abort(403, 'Unauthorized action.'); 
           }
        }

        $ret = call_user_func_array('\\App\\' . $model . '::' . $methods[0]['method'], $methods[0]['params']);

        for($i = 1; $i < count($methods); ++$i)
        {
            $ret = call_user_func_array([$ret, $methods[$i]['method']], $methods[$i]['params']);
        }

        if (is_bool($ret))
            $ret = (string) $ret;
            
        return $ret;
    }
}
