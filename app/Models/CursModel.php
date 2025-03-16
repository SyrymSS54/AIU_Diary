<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CursModel extends Model
{
    protected $connection = "mysql";
    protected $table = "curs_models";
    protected $fillable = [];

    public function program()
    {
        return $this->belongsTo(ProgramModel::class,"parent","id");
    }

    public function subject()
    {
        return $this->hasMany(SubjectModel::class,"id","parent");
    }
}
