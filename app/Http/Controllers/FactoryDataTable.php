<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FactoryDataTable extends Controller{

    public function index(Request $request){

        $model = $request->input('model');
        
        $class = '\\App\\Models\\Headers\\' . $model . 'ModelHeaders';
        $headers = $class::headers;

        return view('factory/datatable', [
            'model' => $model,
            'rowsPerPage' => $request->input('rowsPerPage'),
            'headers' => $headers
        ]);
    }
}