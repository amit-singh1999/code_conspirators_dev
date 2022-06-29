<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class templatemodel extends Model
{
    use HasFactory;
    protected  $table = "template";
    // public $timestamps = false;

    protected $fillable = [
        'name',
        'detail',
    ];
}
