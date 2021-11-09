<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

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
     * @param CommentRequest $request
     * @return Comment
     */
    public function store(CommentRequest $request): Comment {
        return Comment::create([
            "nickname" => $request->input("nickname"),
            "body" => $request->input("body"),
        ]);
    }

    /**
     * @param CommentRequest $request
     * @param $id
     * @return array{status: bool}
     */
    public function update(CommentRequest $request, Comment $comment): array {
        $this->authorize('update', $comment);

        $status = $comment->update([
            "nickname" => $request->input("title"),
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
