<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

class MemoController extends Controller
{
    /**
     * Get all memo items
     *
     * @param Request $request
     * @return App\Models\Memo[]
     */
    public function showMemos(Request $request) {
        return Memo::all();
    }

    /**
     * Get memo by ID
     *
     * @param Request $request
     * @return App\Models\Memo
     */
    public function getMemosById(Request $request, $id) {
        return Memo::find($id);
    }
}
