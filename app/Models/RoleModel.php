<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    protected $connection = "mysql";
    protected $table = "role_models";
    protected $fillable = [];
}
