<?php

namespace App\Models\Headers;

class ObjectModelHeaders {
    const headers = [
        [ "text"=> "ID","align"=> "left","sortable"=> false,"value"=> "id"],
        [ "text"=> "Name", "value"=> "name", "sortable"=> false ],
        [ "text"=> "Address", "value"=> "address", "sortable"=> false ],
        [ "text"=> "Created at", "value"=> "created_at", "sortable"=> false ],
        [ "text"=> "Updated at", "value"=> "updated_at", "sortable"=> false ],
    ];
}