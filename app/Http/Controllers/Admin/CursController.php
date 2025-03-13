<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\CursModel;

class CursController extends Controller
{
    public function list(Request $request,CursModel $cursModel)
    {
        $validator = Validator::make($request->all(),[
            "pro"=>"required|exists:App\Models\ProgramModel,id"
        ]);

        $validated = $validator->safe()->only(['pro']);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>'back',"errors"=>$validator->errors()]);
        }

        $pro = $validated['pro'];
        $curs = $cursModel::where("parent",$pro)->get(["id","parent","number","name","descripton","image","start","final"]);

        return response()->json(["curs"=>$curs,'status'=>true]);
    }

    public function item(Request $request,CursModel $cursModel)
    {
        $validator = Validator::make($request->all(),[
            "pro"=>"required|numeric|exists:App\Models\CursModel,parent",
            "id" => "required|numeric|exists:App\Models\CursModel,id"
        ]);

        $validated = $validator->safe()->only(['pro',"id"]);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>'back',"errors"=>$validator->errors()]);
        }

        $pro = $validated['pro'];
        $id = $validated['id'];
        $curs = $cursModel::where("parent",$pro)->where("id",$id)->get(["id","parent","number","name","descripton","image","start","final"]);

        return response()->json(["curs"=>$curs,'status'=>true]);
    }

    public function create(Request $request,CursModel $cursModel)
    {
        $validator = Validator::make($request->all(),[
            "pro"=>"required|numeric|exists:mysql.App\Models\ProgramModel,parent",
            "number"=>"required|string|unique:mysql.App\Models\CursModel,number",
            "name"=>"required|string|unuqie:mysql.App\Models\CursModel,name",
            "description"=>"required|string",
            "image"=>"required|string",
            "start"=>"required|date",
            "final"=>"required|date",
        ]);

        $validated = $validator->safe()->only(['pro','number','name','description','image','start','fiinal']);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>"back","errors"=>$validator->errors()]);
        }

        $cursModel->created_admin = Auth::id();
        $cursModel->parent = $validated['pro'];
        $cursModel->number = $validated['number'];
        $cursModel->name = $validated['name'];
        $cursModel->description = $validated['description'];
        $cursModel->image = $validated['image'];
        $cursModel->start = $validated['start'];
        $cursModel->final = $validated['final'];

        $cursModel->save();

        return response()->json(['status'=>True]);
    }

    public function update(Request $request,CursModel $cursModel)
    {
        $validator = Validator::make($request->all(),[
            "id" => "required|numeric|exists:mysql.App\Models\CursModel,id",
            "number"=>"string|unique:mysql.App\Models\CursModel,number",
            "name"=>"string|unuqie:mysql.App\Models\CursModel,name",
            "description"=>"string",
            "image"=>"string",
            "start"=>"date",
            "final"=>"date",
        ]);

        $validated = $validator->safe()->only(["id",'number','name','description','image','start','final']);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>"back","errors"=>$validator->errors()]);
        }

        $cursModel = $cursModel::find($validated['id']);

        isset($validated['number']) ?: $cursModel->number = $validated['number'];
        isset($validated['name']) ?: $cursModel->name = $validated['name'];
        isset($validated['description']) ?: $cursModel->description = $validated['description'];
        isset($validated['image']) ?: $cursModel->image = $validated['image'];
        isset($validated['start']) ?: $cursModel->start = $validated['start'];
        isset($validated['final']) ?: $cursModel->final = $validated['final'];

        $cursModel->save();

        return response()->json(['status'=>true]);
    }

    public function delete(Request $request,CursModel $cursModel)
    {
        $validator = Validator::make($request->all(),[
            "pro"=>"required|numeric|exists:App\Models\CursModel,parent",
            "id" => "required|numeric|exists:App\Models\CursModel,id"
        ]);

        $validated = $validator->safe()->only(['pro',"id"]);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>'back',"errors"=>$validator->errors()]);
        }

        $pro = $validated['pro'];
        $id = $validated['id'];
        $cursModel::where("parent",$pro)->where("id",$id)->delete();

        return response()->json(['status'=>true]);
    }
}
