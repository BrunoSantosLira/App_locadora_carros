<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        $credenciais = $request->all(['email','password']);
        $token = auth('api')->attempt($credenciais);
        
        if($token){
            return response()->json(['Token'=> $token]);
        }else{
            return response()->json(['erro' => 'Usuário ou senha inválidos!'], 403);
        }
    }

    public function logout(){
        auth('api')->logout();
        return response()->json(['msg' => 'Logout realizado']);
    }

    public function refresh(){
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }

    public function me(){
        return response()->json(auth()->user());
    }
}
