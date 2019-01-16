<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Object extends Model
{
    protected $fillable = ['address', 'name', 'capacity', 'check_in', 'check_out'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
