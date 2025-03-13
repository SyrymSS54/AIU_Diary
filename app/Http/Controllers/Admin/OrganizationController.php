<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\educational_org_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    public function list(educational_org_model $EduOrgModel)
    {
        $org_list = $EduOrgModel::where("created_admin",Auth::id())->get(['name','address','number','description','image']);

        return response()->json([...$org_list,'status'=>true]);
    }

    public function item(Request $request,educational_org_model $EduOrgModel)
    {
        $validator = Validator::make($request->all(),[
            "id"=>"required|exists:mysql.App/Models/educational_org_model,number"
        ]);

        $validated = $validator->safe()->only(['id']);

        if($validator->fails()){
            return response()->json(['status'=>false,"route"=>"back","errors"=>$validator->errors()]);
        }

        $id = $validated['id'];

        $org = $EduOrgModel::where("created_admin",Auth::id())->where("number",$id)->get(['name','address','number','description','image']);


        return response()->json([...$org,'status'=>true]);
    }

    public function create(Request $request,educational_org_model $EduOrgModel)
    {
        $validator = Validator::make($request->all(),[
            'name'=>"required|string|unique:mysql.App\Models\educational_org_model,name",
            'address'=>"required|string",
            "number"=>"required|string|unique:mysql.App\Models\educational_org_model,number",
            "description"=>"required|string",
            "image"=>"required|string"
        ]);

        $validated = $validator->safe()->only(['name','address','number','description','image']);

        if($validator->fails()){
            return response()->json(['status'=>false,'route'=>"back","errors"=>$validator->errors()]);
        }
        
        $EduOrgModel->created_admin = Auth::id();
        $EduOrgModel->number = $validated['number'];
        $EduOrgModel->address = $validated['address'];
        $EduOrgModel->number = $validated['number'];
        $EduOrgModel->description = $validated['description'];
        $EduOrgModel->image = $validated['image'];

        $EduOrgModel->save();

        return response()->json(['status'=>true]);
    }

    public function update(Request $request,educational_org_model $EduOrgModel)
    {
        $validator = Validator::make($request->all(),[
            "id"=>"required|exists:mysql.App\Models\educational_org_model,id",
            "number"=> "string|unique:mysql.App\Models\educational_org_model,number",
            "address"=>"string",
            "name"=>"string|unique:mysql.App\Models\educational_org_model,name",
            "description"=>"string",
            "image"=>"string"
        ]);

        $validated = $validator->safe()->only(['id','number','name','description','image','address']);

        if($validator->fails()){
            return response()->json(['status'=>true,'route'=>'back','errors'=>$validator->errors()]);
        }

        $EduOrgModel = $EduOrgModel::find($validated['id']);

        $EduOrgModel->created_admin = Auth::id();
        isset($validated['number']) ?: $EduOrgModel->number = $validated['number'];
        isset($validated['name']) ?: $EduOrgModel->name = $validated['name'];
        isset($validated['description']) ?: $EduOrgModel->description = $validated['description'];
        isset($validated['image']) ?: $EduOrgModel->image = $validated['image'];
        isset($validated['address']) ?: $EduOrgModel->address = $validated['address'];

        $EduOrgModel->save();

        return response()->json(['status'=>True]);
    }

    public function delete(Request $request,educational_org_model $EduOrgModel)
    {
        $validator = Validator::make($request->all(),[
            'id'=>"required|exists:mysql.App\Models\educational_org_model,id"
        ]);

        $validated = $validator->safe()->only(['id']);

        if($validator->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validator->errors()]);
        }

        $id = $validated['id'];

        $EduOrgModel = $EduOrgModel::find($id);
        $EduOrgModel->delete();

        return response()->json(['status'=>true]);
    }
}
