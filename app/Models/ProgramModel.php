<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramModel extends Model
{
    protected $connection = "mysql";
    protected $table = "program_models";
    protected $fillable = [];

    public function organization()
    {
        return $this->belongsTo(educational_org_model::class,"parent","id");
    }

    public function curs()
    {
        return $this->hasMany(CursModel::class,"id","parent");
    }
}
