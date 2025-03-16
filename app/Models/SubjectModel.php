<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectModel extends Model
{
    protected $connection = "mysql";
    protected $table = "subject_models";
    protected $fillable = [];

    public function curs()
    {
        return $this->belongsTo(CursModel::class,"parent","id");
    }
}
