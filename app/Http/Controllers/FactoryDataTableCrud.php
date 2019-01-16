<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FactoryDataTableCrud extends Controller{

    public function index(Request $request){

        $model = $request->input('model');
        
        $class = '\\App\\Models\\Crud\\' . $model . 'ModelCrud';
        $headers = $class::getDataTableHeaders();
        $with = $class::getWithRelations();

        return view('factory/datatable-crud', [
            'model' => $model,
            'rowsPerPage' => $request->input('rowsPerPage'),
            'headers' => $headers,
            'with' => $with
        ]);
    }
}