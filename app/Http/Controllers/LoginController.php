<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //
    protected $redirectTo = '/';

    //sanctum
    public function login(Request $r) {

        $data = $r->only(['email','password']);

        if(Auth::attempt($data)) {
            $user = User::where('email', $data['email'])->first();

            $item = time().rand(0,9999);
            $token = $user->createToken($item)->plainTextToken;

            $array['token'] = $token;
            $array['user'] = $user;
        } else {
            $array['error'] = 'E-mail e/ou senha incorretos';
        }
        return $array;
    }

    public function accountRequest (Request $r) {
        $data = $r->only(['email']);

        $user = User::where('email', $data['email'])->first();

        if(!$user) {
            return $array['error'] = 'Conta não encontrada';
        }
        return $user;
    }


    public function register (Request $r) {
        $validator = Validator::make($r->all(), [
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4|confirmed'
        ]);


        if($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()
            ]);
        }

        $data = $r->only(['name','email','password']);

        $user = User::create($data);

        return $user;


    }

    public function logout(Request $r) {
        $array = ['error' => ''];
        $user = $r->user();

        //$array['email'] = $user->email;
        $user->tokens()->delete();

        return $array['result'] = 'Deletado com sucesso';
    }


}
