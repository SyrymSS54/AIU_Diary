<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationProcessModel extends Model
{
    protected $connection = "mysql";
    protected $table = "education_process_models";
    protected $fillable = [];

    public function organization()
    {
        return $this->belongsTo(educational_org_model::class,"org_id","id");
    }

    public function program()
    {
        return $this->belongsTo(ProgramModel::class,"pro_id","id");
    }

    public function curs()
    {
        return $this->belongsTo(CursModel::class,"curs_id","id");
    }

    public function subject()
    {
        return $this->belongsTo(SubjectModel::class,"subject_id","id");
    }
}
