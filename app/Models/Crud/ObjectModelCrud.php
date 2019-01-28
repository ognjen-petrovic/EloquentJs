<?php

namespace App\Models\Crud;

class ObjectModelCrud {
    const headers = [
        [ "text"=> "ID",         "name"=> "id",         "sortable"=> false, "align"=> "left"],
        [ "text"=> "Owner",      "name"=> "user.name",  "sortable"=> false,],
        [ "text"=> "Name",       "name"=> "name",       "sortable"=> false ],
        [ "text"=> "Address",    "name"=> "address",    "sortable"=> false ],
        [ "text"=> "Created at", "name"=> "created_at", "sortable"=> false ],
        [ "text"=> "Updated at", "name"=> "updated_at", "sortable"=> false ],
    ];

    static public function getDataTableHeaders()
    {
        return self::headers;
    }

    static public function getWithRelations()
    {
        return ['user'];
    }

    static public function getEditableAttributes()
    {
        return [
            ['name'=> 'name'],
            ['name'=> 'address']
        ];
    }
}