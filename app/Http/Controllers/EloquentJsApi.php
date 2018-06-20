<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EloquentJsApi extends Controller
{
    public function index(Request $request)
    {
        $methods = [
            'all'      => 'view',
            'count'    => 'view',
            'paginate' => 'view',
            'where'    => 'view',
            'orWhere'  => 'view',
            'get'      => 'view',
            'with'     => 'view',
            'update'   => 'update',
            'orderBy'  => 'view'
        ];

        if (is_null(\Auth::user()))
        {
            \Auth::login(new \App\User());
        }
/*
        var_dump(  \Gate::allows('view', \Auth::user()) );
        var_dump(  \Gate::allows('create', \Auth::user()) );
        var_dump(  \Gate::allows('update', \Auth::user()) );
        var_dump(  \Gate::allows('delete', \Auth::user()) );
        die();
*/


        $payload = json_decode($request->input('payload'), true);
/*
        foreach($payload as $p)
        {
           // if ( \Gate::allows($p['method'], \Auth::user()) )
           var_dump($p['method']);
           var_dump(\Gate::allows($p['method'], \Auth::user()));
        }

        die;
*/
        $ret = call_user_func_array('\\App\\' . $payload[0]['method'], $payload[0]['params']);
        for($i = 1; $i < count($payload); ++$i)
        {
            $ret = call_user_func_array([$ret, $payload[$i]['method']], $payload[$i]['params']);
        }

        if (is_bool($ret))
            $ret = (string) $ret;
            
        return $ret;
    }
}
