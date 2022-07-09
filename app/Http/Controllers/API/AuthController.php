<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $messages = array(
            'password.required' => 'Şifre boş bırakılamaz!',
            'password.min' => 'Şifre minimum 8 karakter olmalı.',
            'email.required' => 'Email boş bırakılamaz.',
            'email.unique' => 'Bu email kullanılmaktadır!',
            'name.required' => 'İsim boş bırakılamaz!',
            'name.max' => 'İsim en fazla 190 karakter olmalıdır!'
        );

        $validate = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'name' => 'required|max:191'
        ],$messages);

        if ($validate->fails()) {
            $fails=array_values($validate->getMessageBag()->toArray())[0];
            $error = $fails[0];
            return response()->json(['status' => false, 'message' => $error]);
        }

        $create_user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($create_user) {
            $tokenStr = $create_user->createToken('register')->plainTextToken;
            return response()->json(['status' => true, 'message' => "Kayıt işleminiz başarılı bir şekilde tamamlandı!",
                'data'=>["token" => $tokenStr, "name" => $create_user->name, "email" => $create_user->email]]);
        }else {
            return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
        }
    }


    public function login(Request $request){
        $messages = array(
            'password.required' => 'Şifre alanı boş bırakılamaz!',
            'password.password' => 'Şifre minimum 8 karakter olmalı.',
            'email.required' => 'Mail boş bırakılamaz.'
        );

        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ],$messages);

        if ($validate->fails()) {
            $fails=array_values($validate->getMessageBag()->toArray())[0];
            $error = $fails[0];
            return response()->json(['status' => false, 'message' => $error]);
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json(['status' => false, 'message' => "E-mail veya şifre hatalı!"]);
        }

        $user = $request->user();
        $tokenStr = $user->createToken('login')->plainTextToken;
        return response()->json(['status' => true, 'message' => "Giriş işleminiz başarılı bir şekilde tamamlandı!",
            'data'=>["token" => $tokenStr, "name" => $user->name, "email" => $user->email]]);

    }
}
