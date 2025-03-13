<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CursModel extends Model
{
    protected $connection = "mysql";
    protected $table = "curs_models";
    protected $fillable = [];
}
