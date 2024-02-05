<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyPlant extends Model
{
    use HasFactory;

    /*
     * show which plants this user owns
     * */
    function plant(){
        return $this->belongsTo(Plant::class);
    }
}
