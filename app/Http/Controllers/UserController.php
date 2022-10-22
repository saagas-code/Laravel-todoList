<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function getAll(Request $r) {
        $users = User::all();
        return $users;
    }
    public function getOne(Request $r) {
        $user = User::find($r->id);

        return $user;
    }
}
