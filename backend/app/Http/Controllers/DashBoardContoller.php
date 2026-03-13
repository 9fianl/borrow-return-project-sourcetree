<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Lend;
use App\Models\User;
use Illuminate\Http\Request;

class DashBoardContoller extends Controller
{
    //
    public function allStats() {
    return response()->json([
        'books' => Book::count(),
        'users' => User::count(),
        'lends' => Lend::count(),
    ]);
}
}
