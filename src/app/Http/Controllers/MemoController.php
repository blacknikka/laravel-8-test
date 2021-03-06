<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemoShowResource;
use Illuminate\Http\Request;
use App\Http\Requests\MemoRequest;
use App\Models\Memo;
use Illuminate\Support\Facades\Auth;

class MemoController extends Controller
{
    /**
     * Get all memo items
     *
     * @param Request $request
     * @return MemoShowResource[]
     */
    public function index(Request $request): iterable {
        return collect(Memo::all())->map(function (Memo $memo) {
            return new MemoShowResource($memo);
        });
    }

    /**
     * Get memo by ID
     *
     * @param Request $request
     * @param number $id
     * @return MemoShowResource
     */
    public function show(Memo $memo): MemoShowResource {
        $this->authorize('view', $memo);

        return new MemoShowResource($memo);
    }

    /**
     * Create Memo
     * @param MemoRequest $request
     * @return Memo
     */
    public function store(MemoRequest $request): Memo {
        return Memo::create(array_merge([
            "title" => $request->input("title"),
            "body" => $request->input("body"),
            "status" => $request->input("status"),
            "author_id" => Auth::id(),
        ], $request->has("is_public") ?
            [
                "is_public" => $request->input("is_public"),
            ]: [],
        ));
    }

    /**
     * @param MemoRequest $request
     * @param $id
     * @return array{status: bool}
     */
    public function update(MemoRequest $request, Memo $memo): array {
        $this->authorize('update', $memo);

        $status = $memo->update(array_merge([
            "title" => $request->input("title"),
            "body" => $request->input("body"),
            "status" => $request->input("status"),
        ], $request->has("is_public") ?
            [
                "is_public" => $request->input("is_public"),
            ]: [],
        ));

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
