<?php

namespace App\Models\Crud;

class UserModelCrud {
    const headers = [
        [ "text"=> "ID",    "name"=> "id","align"=> "left","sortable"=> false],
        [ "text"=> "Name", "name"=> "name", "sortable"=> false ],
        [ "text"=> "Email", "name"=> "email", "sortable"=> false ],
        [ "text"=> "Created at", "name"=> "created_at", "sortable"=> false ],
        [ "text"=> "Updated at", "name"=> "updated_at", "sortable"=> false ],
    ];

    static public function getDataTableHeaders()
    {
        return self::headers;
    }

    static public function getWithRelations()
    {
        return [];
    }

    static public function getEditableAttributes()
    {
        return [
            ['name'=> 'name'],
            ['name'=> 'email']
        ];
    }
}