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
     * @return Memo[]
     */
    public function showMemos(Request $request): iterable {
        return Memo::all();
    }

    /**
     * Get memo by ID
     *
     * @param Request $request
     * @param number $id
     * @return Memo
     */
    public function getMemosById(Request $request, $id): Memo {
        return Memo::findOrFail($id);
    }

    /**
     * Create Memo
     * @param MemoRequest $request
     * @return Memo
     */
    public function createMemos(MemoRequest $request): Memo {
        return Memo::create([
            "title" => $request->input("title"),
            "body" => $request->input("body"),
            "status" => $request->input("status"),
        ]);
    }

    /**
     * @param MemoRequest $request
     * @param $id
     * @return array{status: bool}
     */
    public function updateMemosById(MemoRequest $request, $id): array {
        $status = Memo::where("id", $id)->update([
            "title" => $request->input("title"),
            "body" => $request->input("body"),
            "status" => $request->input("status"),
        ]);

        return [
            'status' => $status,
        ];
    }

    /**
     * Delete Memo by ID
     * @param MemoRequest $request
     * @param $id
     */
    public function deleteMemosById(Request $request, $id): void {
        Memo::where("id", $id)->delete();
    }
}
