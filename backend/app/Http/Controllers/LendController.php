<?php

namespace App\Http\Controllers;

use App\Models\Lend;
use Illuminate\Http\Request;

class LendController extends Controller
{
    //
    public function index(){
        return response()->json(Lend::withTrashed()->get());
    }

    public function returnBook($id){
        $lend = Lend::findOrFail($id);

        $lend->delete();

        return response()->json([
            'message'=>'Returned successfully'
        ]);

    }

    // 2. ปุ่มลบข้อมูล (Force Delete - ลบจริง)
public function destroy($id)
{
    // ใช้ withTrashed() เพื่อให้หาเจอทั้งรายการที่ยืมอยู่และคืนแล้ว
    $lend = Lend::withTrashed()->findOrFail($id);
    
    $lend->forceDelete(); // ลบถาวรออกจากฐานข้อมูล

    return response()->json(['message' => 'ลบข้อมูลถาวรสำเร็จ']);
}

    public function store(Request $request){
        $lend = Lend::create([
            'user_id'=>$request->user_id,
            'book_id'=>$request->book_id,
            'start_date'=>$request->start_date ?? now(),
            'end_time'=>$request->end_time,
        ]);

        return response()->json([
            'message'=>'Lend successfully created',
            'data'=>$lend
        ]);
    }
}
