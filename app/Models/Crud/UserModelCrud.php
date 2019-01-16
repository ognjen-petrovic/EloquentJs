<?php

namespace App\Models\Crud;

class UserModelCrud {
    const headers = [
        [ "text"=> "ID", "value"=> "id","align"=> "left","sortable"=> false],
        [ "text"=> "Name", "value"=> "name", "sortable"=> false ],
        [ "text"=> "Email", "value"=> "email", "sortable"=> false ],
        [ "text"=> "Created at", "value"=> "created_at", "sortable"=> false ],
        [ "text"=> "Updated at", "value"=> "updated_at", "sortable"=> false ],
    ];

    static public function getDataTableHeaders()
    {
        return self::headers;
    }

    static public function getWithRelations()
    {
        return [];
    }
}