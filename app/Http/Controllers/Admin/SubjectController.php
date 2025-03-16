<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubjectModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function list(SubjectModel $subjectModel,Request $request){
        $validator = Validator::make($request->all(),[
            'curs' => 'required|numeric|exists:mysql.App\Models\CursModel,id',
        ]);

        $validated = $validator->safe()->only(['curs']);

        if($validator->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validator->errors()]);
        }

        $curs = $validated['curs'];

        $subjects = $subjectModel::where("parent",$curs)->get(['parent','name','description','number']);
        return response()->json(['subjects'=>$subjects,'status'=>true]);
    }

    public function item(SubjectModel $subjectModel,Request $request){
        $validator = Validator::make($request->all(),[
            'curs'=>'required|numeric|exists:mysql.App\Models\SubjectModel,parent',
            'id' => 'required|numeric|exists:mysql.App\Models\SubjectModel,id'
        ]);

        $validated = $validator->safe()->only(['curs','id']);

        if($validator->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validator->errors()]);
        }

        $curs = $validated['curs'];
        $id = $validated['id'];

        $subjects = $subjectModel::with('curs')->where("parent",$curs)->where('id',$id)->get(['parent','name','description','number']);
        return response()->json(['subjects'=>$subjects,'status'=>true]);
    }

    public function create(SubjectModel $subjectModel,Request $request){
        $validator = Validator::make($request->all(),[
            'parent' => 'required|numeric|exists:mysql.App\Models\CursModel,id',
            'name' => 'required|string',
            'number' => 'required|string|unique:mysql.App\Models\SubjectModel,number',
            'descriprion' => 'required|string'
        ]);

        $validated = $validator->safe()->only(['parent','name','number','description']);

        if($validator->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validator->errors()]);
        }

        $subjectModel->parent = $validated['subject'];
        $subjectModel->name = $validated['name'];
        $subjectModel->number = $validated['number'];
        $subjectModel->description = $validated['description'];

        $subjectModel->save();

        return response()->json(['status'=>true]);
    }

    public function update(SubjectModel $subjectModel,Request $request){
        $validator = Validator::make($request->all(),[
            'id' => 'required|numeric|exists:mysql.App\Models\SubjectModel,id',
            'parent' => 'numeric|exists:mysql.App\Models\CursModel,id',
            'name' => 'string',
            'number' => 'string|unique:mysql.App\Models\SubjectModel,number',
            'description' => 'string'
        ]);

        $validated = $validator->safe()->only(['id','parent','name','number','description']);

        if($validator->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validator->errors()]);
        }

        $subjectModel= $subjectModel::find($validated['id']);

        isset($validated['parent']) ?: $subjectModel->parent = $validated['parent'];
        isset($validated['name']) ?: $subjectModel->name = $validated['name'];
        isset($validated['number']) ?: $subjectModel->number = $validated['number'];
        isset($validated['description']) ?: $subjectModel->description = $validated['description'];


        $subjectModel->save();

        return response()->json(['status'=>true]);
    }

    public function delete(SubjectModel $subjectModel,Request $request){
        $validator = Validator::make($request->all(),[
            'curs'=>'required|numeric|exists:mysql.App\Models\SubjectModel,parent',
            'id' => 'required|numeric|exists:mysql.App\Models\SubjectModel,id'
        ]);

        $validated = $validator->safe()->only(['curs','id']);

        if($validator->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validator->errors()]);
        }

        $curs = $validated['curs'];
        $id = $validated['id'];

        $subjects = $subjectModel::where("parent",$curs)->where('id',$id);
        $subjects->delete();

        return response()->json(['status'=>true]);
    }
}
