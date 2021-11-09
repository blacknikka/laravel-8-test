<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Comment;
use App\Models\Memo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param bool $is_public
     * @param array $param
     * @return array{user1: User, user2: User, memo: Collection<Memo>, comments: Collection<Comment>}
     */
    private function createCommentsWithRelationship(bool $is_public, array $param = []): array {
        $user = User::factory()->create();
        $memo = Memo::factory()
            ->for($user, 'author')
            ->create([
            'is_public' => $is_public,
        ]);
        $comments = Comment::factory()
            ->for($user, 'author')
            ->for($memo, 'memo')
            ->count(2)->create(
                $param,
            );

        $user2 = User::factory()->create();
        $comments2 = Comment::factory()
            ->for($user2, 'author')
            ->for($memo, 'memo')
            ->count(2)->create(
                $param,
            );

        return [
            'user1' => $user,
            'user2' => $user2,
            'memo' => $memo,
            'comments' => $comments->concat($comments2),
        ];
    }

    /** @test */
    public function publicなmemoに紐づいたcommentが取得できる()
    {
        ['user1' => $user1, 'user2' => $user2, 'memo' => $memo, 'comments' => $comments]
            = $this->createCommentsWithRelationship(true);
        $response = $this->actingAs($user1)->getJson("/api/comments/{$comments[0]->id}");
        $response
            ->assertStatus(200)
            ->assertJson(
                $comments[0]->jsonSerialize()
            );
    }
}
