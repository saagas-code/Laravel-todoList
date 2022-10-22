<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function getAll(Request $r) {
        $categories = DB::table('categories')
            ->select('categories.*')
            ->where('categories.user_id', '=', $r->id)
            ->get();
        return $categories;
    }
    public function getOne(Request $r) {
        $category = Category::find($r->id);
        $category['user'] = $category->user;
        return $category;
    }

    public function create(Request $r) {
        $validator = Validator::make($r->only(['title','color','user_id']), [
            'title' => 'required', Rule::unique('categories')->where(fn ($query) => $query->where('user_id', $r->user_id)),
            'color' => 'required',
            'user_id' => 'required'
        ]); //Rule::unique('categories')->where('user_id', $r->user_id)

        if($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        $data = $r->only(['title','color','user_id']);
        $user = Category::create($data);
        return $array['result'] = $user;
    }
}
