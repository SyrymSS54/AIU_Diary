<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramModel extends Model
{
    protected $connection = "mysql";
    protected $table = "program_models";
    protected $fillable = [];
}
