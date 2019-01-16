<?php

namespace App\Models\Crud;

class ObjectModelCrud {
    const headers = [
        [ "text"=> "ID",         "value"=> "id",         "sortable"=> false, "align"=> "left"],
        [ "text"=> "Owner",      "value"=> "user.name",  "sortable"=> false,],
        [ "text"=> "Name",       "value"=> "name",       "sortable"=> false ],
        [ "text"=> "Address",    "value"=> "address",    "sortable"=> false ],
        [ "text"=> "Created at", "value"=> "created_at", "sortable"=> false ],
        [ "text"=> "Updated at", "value"=> "updated_at", "sortable"=> false ],
    ];

    static public function getDataTableHeaders()
    {
        return self::headers;
    }

    static public function getWithRelations()
    {
        return ['user'];
    }
}