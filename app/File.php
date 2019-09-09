<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'file_id',
        'slug',
        'name',
        'type',
        'url'
    ];
}
