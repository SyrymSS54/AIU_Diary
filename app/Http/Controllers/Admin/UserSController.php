<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserSController extends Controller
{
    /**
     * Вывод списка пользователей
     * Method: post
     * Resource: /users/list
     * Request: null
     * Return: json
     */
    public function list(User $user)
    {
        $users = $user::where("role","NOT LIKE","admin")->paginate(15,['id','first_name','last_name','email','role']);

        return response()->json($users);
    }

    /**
     * Вывод значение конкретного пользователя
     * Method: post
     * Resource: /users/шеуь
     * Request: id
     * Return: json
     */
    public function item(User $user,Request $request)
    {
        $validated = Validator::make($request->all(),[
            "id"=>"required|numeric",
        ]);

        $validated_data = $validated->safe()->only(['id']);

        if($validated->fails()){
            return response()->json(['status'=>false,'route'=>'back',,'errors'=>$validated->errors()],422);
        }

        $id = $validated_data['id'];
        $users = $user::where("role","NOT LIKE","admin")->where("id",$id)->first(['id','first_name','last_name','email','role',"password"]);
        
        if(is_null($users)){
            return response()->json(['status'=>false,'route'=>'back','reason' => 'result is null']);
        }

        $users['status'] = true;
        return response()->json($users);
    }

    /**
     * Регистрации пользователя
     * Method: post
     * Resource: /users/create
     * Request: first_name last_name email role password
     * Return: json
     */
    public function create(User $user,Request $request)
    {
        $validated = Validator::make($request->all(),[
            "first_name" => 'required|string',
            "last_name" => 'required|string',
            "email" => 'required|email|unique:mysql.App/Models/User,email',
            "role" => "required|exists:mysql.app/Models/RoleModel,role",
            "password" => "required|between:8,24"
        ]);

        $validated_data = $validated->safe()->only(["first_name","last_name","email","role","password"]);

        if($validated->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validated->errors()],422);
        }

        $first_name = $validated_data['first_name'];
        $last_name = $validated_data['last_name'];
        $email = $validated_data['email'];
        $role = $validated_data['role'];
        $password = Hash::make($validated_data['password']);

        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->role = $role;
        $user->password = $password;

        $user->save();

        return response()->json(['status'=>true]);
    }

    /**
     * Обновил пользователя
     * Method: post
     * Resource: /users/update
     * Request: id, first_name, last_name, email, role, password
     * Return: json
     */

    public function update(User $user,Request $request)
    {
        $validated = Validator::make($request->all(),[
            "id" => "required|numeric|exists:mysql.App/Models/User,id",
            "first_name" => 'string',
            "last_name" => 'string',
            "email" => 'email|unique:mysql.App/Models/User,email',
            "role" => "exists:mysql.App/Models/RoleModel,role",
            "password" => "between:8,24"
        ]);

        $validated_data = $validated->safe()->only(["id","first_name","last_name","email","role","password"]);

        if($validated->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validated->errors()],422);
        }

        $id = $validated_data['id'];
        $user = $user::where('role',"NOT LIKE","admin")->where("id",$id);

        isset($validated_data['first_name']) ?: $user->first_name = $validated_data['first_name'];
        isset($validated_data['last_name']) ?: $user->last_name = $validated_data['last_name'];
        isset($validated_data['email']) ?: $user->email = $validated_data['email'];
        isset($validated_data['role']) ?: $user->role = $validated_data['role'];
        isset($validated_data['password']) ?: $user->email = Hash::make($validated_data['password']);

        $user->save();

        return response()->json(['status'=>true]);
    }

    /**
     * Удаление пользователя
     * Method: post
     * Resource: /users/delete
     * Request: id
     * Return: json
     */
    public function delete(User $user,Request $request)
    {
        $validated = Validator::make($request->all(),[
            "id" => "required|numeric|exists:mysql.App/Models/User,id"
        ]);

        $validated_data = $validated->safe()->only(["id"]);

        if($validated->fails()){
            return response()->json(['status'=>false,'route'=>'back','errors'=>$validated->errors()],422);
        }

        $id = $validated_data['id'];
        $user = $user::where('role',"NOT LIKE","admin")->where("id",$id);

        $user->delete();

        return response()->json(['status'=>true]);
    }
}
