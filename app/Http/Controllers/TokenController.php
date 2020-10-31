<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenController extends Controller
{
    public function getToken(\Tymon\JWTAuth\JWTAuth $auth ){
        $user=Auth::user();
        $token = $auth->fromUser($user,['id'=>$user->id, 'email'=>$user->email]);
        return response()->json(['token'=>$token]);
    }
}
