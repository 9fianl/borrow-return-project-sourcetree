<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Lend;
use Illuminate\Http\Request;


class BookController extends Controller
{
    //
    public function index(){
        // ใช้ withExists จะได้ตัวแปร lends_exists พ่วงมากับหนังสือแต่ละเล่ม
    // ค่าจะเป็น true (มีคนยืมอยู่) หรือ false (ไม่มีคนยืม/คืนหมดแล้ว)
        return response()->json(Book::withExists('lends')->get());
    }

    public function store(Request $request){

    $image = null;

    if($request->hasFile('image_path')){
        $image = $request->file('image_path')->store('books','public');
    }

        $book = Book::create([
            'book_name'=>$request->book_name,
            'image_path'=>$image,
            'year'=>$request->year,
            'category_id'=>$request->category_id,
        ]);
        
        return response()->json([
            'message'=>'Book successfully created',
            'data'=>$book
        ]);
    }


    public function update(Request $request, $id){

        $book = Book::findOrFail($id);

    $image = $book->image_path;

    if($request->hasFile('image_path')){
        $image = $request->file('image_path')->store('books','public');
    }


        $book->update([
            'book_name'=>$request->book_name,
            'image_path'=>$image,
            'year'=>$request->year,
            'category_id'=>$request->category_id,
        ]);
        
        return response()->json([
            'message'=>'Book successfully updated',
            'data'=>$book
        ]);
    }

    public function destroy($id){

    $book = Book::findOrFail($id);
    
    $book->delete();

    return response()->json([
        'message'=>'Books successfully deleted',
        'data'=>$book
    ]);

    }

    
    
    
}
