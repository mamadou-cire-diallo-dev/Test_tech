<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class AuthController extends Controller
{
    //

    /**
     * @param AuthRequest $request
     * @return JsonResponse
     */


    public function login(AuthRequest $request){

        $credentials = $request->validated();

//        dd($credentials);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }


        /**
         * @var $user User
         */


        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'message' => 'Connexion réussis',
            'user'=>$user,
            'token'=>$token], 200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "message"=>'Déconnexion réussis'
        ]);
    }
}


