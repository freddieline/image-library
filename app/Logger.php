<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logger extends Model
{
    protected $table = 'logs';
    protected $fillable = [
        'id', 'image-filename', 'deleted', 'sent', 'failed', 'created_at', 'updated_at'
    ];
}
