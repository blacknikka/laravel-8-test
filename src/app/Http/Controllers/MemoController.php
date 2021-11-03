<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

class MemoController extends Controller
{
    public function showMemos(Request $request) {
        return Memo::all();
    }
}
