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
    public function index(Request $request): iterable {
        return Memo::all();
    }

    /**
     * Get memo by ID
     *
     * @param Request $request
     * @param number $id
     * @return Memo
     */
    public function show(Memo $memo): Memo {
        $this->authorize('view', $memo);

        return $memo;
    }

    /**
     * Create Memo
     * @param MemoRequest $request
     * @return Memo
     */
    public function store(MemoRequest $request): Memo {
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
    public function update(MemoRequest $request, Memo $memo): array {
        $this->authorize('update', $memo);

        $status = $memo->update([
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
    public function destroy(Request $request, Memo $memo): void {
        $this->authorize('delete', $memo);

        $memo->delete();
    }
}
