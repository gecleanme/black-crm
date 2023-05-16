<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    public function attachable() //this has to match the related models, /table column name
    {
        $this->morphTo(); //instead of belongsTo
    }
}
