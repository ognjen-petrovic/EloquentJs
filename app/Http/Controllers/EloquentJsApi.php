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
            'orderBy'  => 'view',
            'find'     => 'view',
            'update'   => 'update',
            'updateOrCreate' => 'create',
            'create' => 'create',
            'destroy' => 'delete'
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

        $ret[0] = call_user_func_array('\\App\\' . $model . '::' . $methods[0]['method'], $methods[0]['params']);

        for($i = 1; $i < count($methods); ++$i)
        {
            $ret[$i] = call_user_func_array([$ret[$i-1], $methods[$i]['method']], $methods[$i]['params']);
            if ($ret[$i-1] instanceof \App\Model && $ret[$i-1]->hasErrors())
            {
                return response($ret[$i-1]->getErrors()->toArray(), 422); 
            }
        }
     
        $ret = end($ret);
        if (is_bool($ret))
        {
            if ($ret == true)
            {
                $ret = 1;
            }
            else
            {
                $ret = 0;
            }
                
        }
            
        return $ret;
    }
}
