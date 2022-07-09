<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller{
    public function user(){
        $user = auth('sanctum')->user();
        return response()->json(['status' => true, 'message' => "Kullanıcı Bilgileri Başarıyla Getirlidi",
            'data'=> $user->only(["name","phone","email"])]);
    }

    public function user_update(Request $request){
        $messages = array(
            'name.required' => 'Ad soyad alanı boş bırakılamaz!',
            'name.max' => 'İsim ve soyisim maximum 191 karakteri aşmamalı!',
            'email.required' => 'Enail boş bırakılamaz.',
            'email.unique' => ' Bu email kullanılmaktadır!'
        );

        $validate = Validator::make($request->all(), [
            "name"=>"required|max:255",
            "email"=>"required|email"
        ],$messages);

        if ($validate->fails()) {
            $fails=array_values($validate->getMessageBag()->toArray())[0];
            $error = $fails[0];
            return response()->json(['status' => false, 'message' => $error]);
        }

        $user = auth('sanctum')->user();
        $mail = User::where('email', '!=' ,$request->mail)
            ->where('id', '!=' ,$user->id)
            ->first();

        if($mail != null){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $saved =$user->save();
            if ($saved){
                return response()->json(['status' => true, 'message' => "Güncelleme Başarılı."]);
            }else{
                return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
            }
        }else{
            return response()->json(['status' => false, 'message' => "Bu Mail Adresi kullanılmaktadır."]);
        }
    }

    public function change_password(Request $request){
        $messages = array(
            'password.required' => 'Şifre boş bırakılamaz!',
            'password.min' => 'Şifre minimum 8 karakter olmalı.',
        );

        $validate = Validator::make($request->all(), [
            'password' => 'required|min:8'
        ],$messages);

        if ($validate->fails()) {
            $fails=array_values($validate->getMessageBag()->toArray())[0];
            $error = $fails[0];
            return response()->json(['status' => false, 'message' => $error]);
        }

        $user = auth('sanctum')->user();
        $user->password = Hash::make($request->password);
        $saved =$user->save();

        if ($saved){
            DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();
            /*foreach ($user->tokens as $token) {
                $token->delete();
            }*/
            $tokenStr = $user->createToken('login')->plainTextToken;
            return response()->json(['status' => true, 'message' => "Güncelleme Başarılı.",
                'data'=>["token" => $tokenStr, "name" => $user->name, "email" => $user->email]]);
        }else{
            return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
        }

    }

}
