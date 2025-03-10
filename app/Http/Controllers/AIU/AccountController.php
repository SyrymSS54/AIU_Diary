<?php

namespace App\Http\Controllers\AIU;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class AccountController extends Controller
{
    /**
     * Страница входа.
     * Method:get
     * Resource: /
     * Return: view
     */
    public function index(){
        return view('Auth.signin');
    }

    /**
     * Проверка данные на вход,созданние сессии и редиректизации на свою страницу.
     * Method: post
     * Resource: /signin
     * Request: email,password
     * Return: redirect
     */
    public function signin(Request $request){
        $validator = Validator::make(
            $request->all(),// данные входа
            [
                'email' => 'required|email',
                'password' => 'required|min:8'
            ], // правила валидации
            [
                'email.required' => 'email не заполнен',
                'email.email' => "email не является электронный почтой",
                'password.required' => 'password не заполнен',
                'password.min' => 'password должен быть больше 8'
            ], //сообщение ошибок
            [
                'email' => 'электронная почта',
                'password' => 'пароль'
            ] // аттрибуты для сообщений
        );

        //получение данных валидации
        $credentials = $validator->safe()->only(['email','password']);

        //отправка ошибок если они есть
        if($validator->fails()){
            return response()->json(['route'=>'back','status'=>false,'errors'=>$validator->errors()]);
        }

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            $role=Auth::user()['role'];

            if($role=='admin')
            {
                return response()->json(['route'=>'admin','status'=>True]);
            }
            else if($role=='teacher')
            {
                return response()->json(['route'=>'teacher','status'=>True]);
            }
            else if($role=='student')
            {
                return response()->json(['route'=>'student','status'=>True]);
            }
            else{
                //обновление состояние и выход из аккаунта
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json(['route'=>'back','status'=>False,'reason'=>'wrong role']);
            }
        }

        return response()->json(['route'=>'back','status'=>False,'reason'=>'no auth']);
    }

    /**
     * Страница входа.
     * Method:get
     * Resource: /admin
     * Return: view
     */
    public function admin_page(){
        return view('Admin.index');
    }

    /**
     * Страница входа.
     * Method:get
     * Resource: /teacher
     * Return: view
     */
    public function teacher_page(){
        return view('Teacher.index');
    }

    /**
     * Страница входа.
     * Method:get
     * Resource: /student
     * Return: view
     */
    public function student_page(){
        return view('Student.index');
    }
}
