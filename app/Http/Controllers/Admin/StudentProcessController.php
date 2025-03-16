<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CursModel;
use App\Models\ProgramModel;
use Illuminate\Http\Request;
use App\Models\EducationProcessModel;
use App\Models\SubjectModel;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class StudentProcessController extends Controller
{
    public function students(EducationProcessModel $educationProcessModel,Request $request,User $user)
    {
        $students = $user::doesntHave('organization')->where("role","student")->get(['email','role','first_name','last_name','id']);

        return response()->json(['teachers'=>$students,'status'=>true]);
    }

    public function list(EducationProcessModel $educationProcessModel,Request $request)
    {
        $validator = Validator::make($request->all(),[
            'org' => 'required|numeric|exists:mysql.App\Models\educational_org_model,id'
        ]);

        $validated = $validator->safe()->only(['org']);

        if($validator->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validator->errors()]);
        }

        $org = $validated['org'];
        $students = $educationProcessModel::with(["organization","program","curs","subject"])->where("role","student")->where("org_id",$org)->get();

        return response()->json(['status'=>true,'teachers'=>$students]);
    }

    public function get_process(EducationProcessModel $educationProcessModel,Request $request)
    {
        $validator = Validator::make($request->all(),[
            "organization" => "numeric|exists:mysql.App\Models\educational_org_model,id",
            "program" => "numeric|exists:mysql.App\Models\ProgramModel,id",
            "curs" => "numeric|exists:mysql.App\Models\CursModel,id",
            "subject" => "numeric|exists:mysql.App\Models\SubjectModel,id",
        ]);

        $validated = $validator->safe()->only(['organization','program','curs','subject']);

        if($validator->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validator->errors()]);
        }

        $educationProcessModel = $educationProcessModel::with(["organization","program","curs","subject"])->where('role','student');;

        isset($validated['organization']) ?: $educationProcessModel=$educationProcessModel->where("org_id",$validated['organization']);
        isset($validated['program']) ?: $educationProcessModel=$educationProcessModel->where("pro_id",$validated['program']);
        isset($validated['curs']) ?: $educationProcessModel=$educationProcessModel->where('curs_id',$validated['curs']);
        isset($validated['subject']) ?: $educationProcessModel=$educationProcessModel->where("subject_id",$validated['subject_id']);

        return response()->json(['status'=>true,'process'=>$educationProcessModel->get()]);
    }

    public function create(EducationProcessModel $educationProcessModel,Request $request)
    {
        $validator = Validator::make($request->all(),[
            "user_id" => "required|numeric|exists:mysql.App\Models\User,id",
            "organization" => "required|numeric|exists:mysql.App\Models\educational_org_model,id",
            "program" => "numeric|exists:mysql.App\Models\ProgramModel,id",
            "curs" => "numeric|exists:mysql.App\Models\CursModel,id",
            "subject" => "numeric|exists:mysql.App\Models\SubjectModel,id",
        ]);

        $validated = $validator->safe()->only(['user_id','organization','program','curs','subject']);

        if($validator->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validator->errors()]);
        }

        $educationProcessModel->user_id = $validated['user_id'];
        $educationProcessModel->role = 'student';
        $educationProcessModel->org_id = $validated['organization'];

        if(isset($validated['program']) & ProgramModel::find($validated['program'])->parent == $validated['organization']){
            $educationProcessModel->pro_id = $validated['program'];
            
            if(isset($validated['curs']) & CursModel::find($validated['curs'])->parent == $validated['program']){
                $educationProcessModel->curs_id = $validated['curs'];

                if(isset($validated['subject']) & SubjectModel::find($validated['subject'])->parent == $validated['curs']){
                    $educationProcessModel->subject_id = $validated['subject'];

                    return response()->json(['status'=>true,'route'=>'ahead']);
                }
            }
        }

        return response()->json(['status'=>true,'route'=>"back"]);
    }

    public function update(EducationProcessModel $educationProcessModel,Request $request)
    {
        $validator = Validator::make($request->all(),[
            "user_id" => "required|numeric|exists:mysql.App\Models\User,id",
            "organization" => "required|numeric|exists:mysql.App\Models\educational_org_model,id",
            "program" => "numeric|exists:mysql.App\Models\ProgramModel,id",
            "curs" => "numeric|exists:mysql.App\Models\CursModel,id",
            "subject" => "numeric|exists:mysql.App\Models\SubjectModel,id",
        ]);

        $validated = $validator->safe()->only(['user_id','organization','program','curs','subject']);

        if($validator->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validator->errors()]);
        }

        $educationProcessModel = $educationProcessModel::where("user_id",$validated['user_id']);

        $old_org_id = $educationProcessModel->org_id;
        $old_pro_id = $educationProcessModel->pro_id;
        $old_curs_id = $educationProcessModel->curs_id;
        $old_subject = $educationProcessModel->subject_id;

        isset($validated['organization']) & $old_org_id !== $validated['organization'] ? 
        $educationProcessModel->org_id = $validated['organization'] : $validated['orgnization'] = $old_org_id;

        isset($validated['program']) & $old_pro_id !== $validated['program'] & ProgramModel::find($validated['program'])->parent == $validated['organzation'] ?
            $educationProcessModel->pro_id = $validated['program'] :
            $validated['program'] = $old_pro_id;
        
        isset($validated['curs']) & $old_curs_id !== $validated['curs'] & CursModel::find($validated['curs'])->parent == $validated['program'] ?
            $educationProcessModel->curs_id = $validated['curs'] :
            $validated['curs'] = $old_curs_id;

        isset($validated['subject']) & $old_subject !== $validated['subject'] & SubjectModel::find($validated['subject'])->parent == $validated['curs'] ?:
            $educationProcessModel->subject_id = $validated['subject'] ;

        $educationProcessModel->save();

        return response()->json(['status'=>true]);
    }

    public function delete(EducationProcessModel $educationProcessModel,Request $request)
    {
        $validator = Validator::make($request->all(),[
            "id" => 'required|numeric|exists:mysql.App\Models\EducationProcessModel,id'
        ]);

        $validated = $validator->safe()->only(['id']);

        if($validator->fails()){
            return response()->json(['status'=>false,'errors'=>$validator->errors()]);
        }
        $id = $validated['id'];

        $educationProcessModel::find($id)->delete();
        return response()->json(['status'=>true]);
    }
}
