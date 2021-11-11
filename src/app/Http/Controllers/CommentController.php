<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Get comment by ID
     *
     * @param Request $request
     * @param number $id
     * @return Comment
     */
    public function show(Comment $comment): Comment {
        $this->authorize('view', $comment);

        return $comment;
    }

    /**
     * Create Comment
     * @param CommentUpdateRequest $request
     * @return Comment
     */
    public function store(CommentUpdateRequest $request): Comment {
        return Comment::create([
            "nickname" => $request->input("nickname"),
            "body" => $request->input("body"),
            "memo_id" => $request->input("memo_id"),
            "author_id" => Auth::id(),
        ]);
    }

    /**
     * @param CommentUpdateRequest $request
     * @param $id
     * @return array{status: bool}
     */
    public function update(CommentUpdateRequest $request, Comment $comment): array {
        $this->authorize('update', $comment);

        $status = $comment->update([
            "nickname" => $request->input("nickname"),
            "body" => $request->input("body"),
        ]);

        return [
            'status' => $status,
        ];
    }

    /**
     * Delete Comment by ID
     * @param $id
     */
    public function destroy(Comment $comment): void {
        $this->authorize('delete', $comment);

        $comment->delete();
    }
}
