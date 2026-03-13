<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index(){
        return response()->json(Category::withCount('books')->get());
    }

    public function store(Request $request){
        $category = Category::create([
            'category_name'=>$request->category_name
        ]);

        return response()->json([
            'message'=>'Category Successfully created',
            'data'=>$category
        ]);
    }

    public function update(Request $request,$id){
        $category = Category::findOrFail($id);

        $category->update([
            'category_name'=>$request->category_name
        ]);

        return response()->json([
            'message'=>'Category successfully updated',
            'data'=>$category
        ]);
    }

    public function destroy($id){
        $category = Category::findOrFail($id);

        $category->delete();

        return response()->json([
            'message'=>'Category sucessfully deleted'
        ]);

    }

}
