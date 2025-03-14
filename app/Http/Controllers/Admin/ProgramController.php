<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            "image"=>"required|image",
            "start"=>"required|date",
            "final"=>"required|date",
        ]);

        $validated = $validator->safe()->only(['org','number','name','description','start','fiinal']);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>"back","errors"=>$validator->errors()]);
        }

        //Работа с файлами
        $image = $request->file('image');
        $filename = uniqid() . '.' . $image->getClientOriginalExtension();

        Storage::disk('program_preview')->putFileAs('',$image,$filename);
        //Окончиваем работа с файлами

        $programModel->created_admin = Auth::id();
        $programModel->parent = $validated['org'];
        $programModel->number = $validated['number'];
        $programModel->name = $validated['name'];
        $programModel->description = $validated['description'];
        $programModel->image = $filename;
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
            "image"=>"image",
            "start"=>"date",
            "final"=>"date",
        ]);

        $validated = $validator->safe()->only(["id",'number','name','description','start','final']);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>"back","errors"=>$validator->errors()]);
        }

        $programModel = $programModel::find($validated['id']);

        //Работа с файлами
        if($request->hasFile('image')){
            //удалить файл
            Storage::disk('program_preview')->delete($programModel->image);

            $image = $request->file('image');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
    
            Storage::disk('program_preview')->putFileAs('',$image,$filename);
            $programModel->image = $filename;
        }
        //Окончиваем работа с файлами

        isset($validated['number']) ?: $programModel->number = $validated['number'];
        isset($validated['name']) ?: $programModel->name = $validated['name'];
        isset($validated['description']) ?: $programModel->description = $validated['description'];
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

        $programModel = $programModel::where("parent",$org)->where("id",$id);
        Storage::disk('program_preview')->delete($programModel->image);
        $programModel->delete();

        return response()->json(['status'=>true]);
    }
}
