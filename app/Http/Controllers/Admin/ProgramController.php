<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function list(Request $request,ProgramModel $programModel)
    {
        $validator = Validator::make($request->all(),[
            "org"=>"required|exists:App\Models\educational_org_model,id"
        ]);

        $validated = $validator->safe()->only(['org']);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>'back',"errors"=>$validator->errors()]);
        }

        $id = $validated['org'];
        $programs = $programModel::where("parent",$id)->get(["id","parent","number","name","descripton","image","start","final"]);

        return response()->json(["programs"=>$programs,'status'=>true]);
    }

    public function item(Request $request,ProgramModel $programModel)
    {
        $validator = Validator::make($request->all(),[
            "org"=>"required|numeric|exists:App\Models\educational_org_model,parent",
            "id" => "required|numeric|exists:App\Models\ProgramModel,id"
        ]);

        $validated = $validator->safe()->only(['org',"id"]);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>'back',"errors"=>$validator->errors()]);
        }

        $org = $validated['org'];
        $id = $validated['id'];
        $programs = $programModel::where("parent",$org)->where("id",$id)->get(["id","parent","number","name","descripton","image","start","final"]);

        return response()->json(["programs"=>$programs,'status'=>true]);
    }

    public function create(Request $request,ProgramModel $programModel)
    {
        $validator = Validator::make($request->all(),[
            "org"=>"required|numeric|exists:mysql.App\Models\educational_org_model,parent",
            "number"=>"required|string|unique:mysql.App\Models\ProgramModel,number",
            "name"=>"required|string|unuqie:mysql.App\Models\ProgramModel,name",
            "description"=>"required|string",
            "image"=>"required|string",
            "start"=>"required|date",
            "final"=>"required|date",
        ]);

        $validated = $validator->safe()->only(['org','number','name','description','image','start','fiinal']);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>"back","errors"=>$validator->errors()]);
        }

        $programModel->created_admin = Auth::id();
        $programModel->parent = $validated['org'];
        $programModel->number = $validated['number'];
        $programModel->name = $validated['name'];
        $programModel->description = $validated['description'];
        $programModel->image = $validated['image'];
        $programModel->start = $validated['start'];
        $programModel->final = $validated['final'];

        $programModel->save();

        return response()->json(['status'=>True]);
    }

    public function update(Request $request,ProgramModel $programModel)
    {
        $validator = Validator::make($request->all(),[
            "id" => "required|numeric|exists:mysql.App\Models\ProgramModel,id",
            "number"=>"string|unique:mysql.App\Models\ProgramModel,number",
            "name"=>"string|unuqie:mysql.App\Models\ProgramModel,name",
            "description"=>"string",
            "image"=>"string",
            "start"=>"date",
            "final"=>"date",
        ]);

        $validated = $validator->safe()->only(["id",'number','name','description','image','start','final']);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>"back","errors"=>$validator->errors()]);
        }

        $programModel = $programModel::find($validated['id']);

        isset($validated['number']) ?: $programModel->number = $validated['number'];
        isset($validated['name']) ?: $programModel->name = $validated['name'];
        isset($validated['description']) ?: $programModel->description = $validated['description'];
        isset($validated['image']) ?: $programModel->image = $validated['image'];
        isset($validated['start']) ?: $programModel->start = $validated['start'];
        isset($validated['final']) ?: $programModel->final = $validated['final'];

        $programModel->save();

        return response()->json(['status'=>true]);
    }

    public function delete(Request $request,ProgramModel $programModel)
    {
        $validator = Validator::make($request->all(),[
            "org"=>"required|numeric|exists:App\Models\ProgramModel,parent",
            "id" => "required|numeric|exists:App\Models\ProgramModel,id"
        ]);

        $validated = $validator->safe()->only(['org',"id"]);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>'back',"errors"=>$validator->errors()]);
        }

        $org = $validated['org'];
        $id = $validated['id'];
        $programModel::where("parent",$org)->where("id",$id)->delete();

        return response()->json(['status'=>true]);
    }
}
