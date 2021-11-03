<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MemoRequest;
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
     * @param number $id
     * @return App\Models\Memo
     */
    public function getMemosById(Request $request, $id) {
        return Memo::find($id);
    }

    /**
     * @param MemoRequest $request
     * @param $id
     */
    public function updateMemosById(MemoRequest $request, $id) {
        $memo = Memo::where("id", $id)->update([
            "title" => $request->input("title"),
            "body" => $request->input("body"),
            "status" => $request->input("status"),
        ]);
    }
}
