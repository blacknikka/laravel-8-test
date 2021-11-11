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
        $memo_owner = User::factory()->create();
        $memo = Memo::factory()
            ->for($memo_owner, 'author')
            ->create([
            'is_public' => $is_public,
        ]);
        $comments = Comment::factory()
            ->for($memo_owner, 'author')
            ->for($memo, 'memo')
            ->count(2)->create(
                $param,
            );

        $other = User::factory()->create();
        $comments2 = Comment::factory()
            ->for($other, 'author')
            ->for($memo, 'memo')
            ->count(2)->create(
                $param,
            );

        return [
            'memo_owner' => $memo_owner,
            'other' => $other,
            'memo' => $memo,
            'comments' => $comments->concat($comments2),
        ];
    }

    /** @test */
    public function publicなmemoに紐づいたcommentが取得できる()
    {
        ['memo_owner' => $memo_owner, 'other' => $other, 'memo' => $memo, 'comments' => $comments]
            = $this->createCommentsWithRelationship(true);
        $response = $this->actingAs($memo_owner)->getJson("/api/comments/{$comments[0]->id}");
        $response
            ->assertStatus(200)
            ->assertJson(
                $comments[0]->jsonSerialize()
            );
    }

    /** @test */
    public function privateなmemoに紐づいたcommentが取得できる_データのオーナー()
    {
        ['memo_owner' => $memo_owner, 'other' => $other, 'memo' => $memo, 'comments' => $comments]
            = $this->createCommentsWithRelationship(false);
        $response = $this->actingAs($memo_owner)->getJson("/api/comments/{$comments[0]->id}");
        $response
            ->assertStatus(200)
            ->assertJson(
                $comments[0]->jsonSerialize()
            );
    }

    /** @test */
    public function privateなmemoに紐づいたcommentが取得できない_データのオーナー以外()
    {
        ['memo_owner' => $memo_owner, 'other' => $other, 'memo' => $memo, 'comments' => $comments]
            = $this->createCommentsWithRelationship(false);
        $response = $this->actingAs($other)->getJson("/api/comments/{$comments[0]->id}");
        $response
            ->assertStatus(403);
    }

    /** @test */
    public function publicなmemoにcommentをstoreできる()
    {
        ['memo_owner' => $memo_owner, 'other' => $other, 'memo' => $memo, 'comments' => $comments]
            = $this->createCommentsWithRelationship(true);

        $expected = [
            'nickname' => 'name',
            'body' => 'bbb',
            'memo_id' => $memo->id,
        ];

        // test
        $response = $this->actingAs($memo_owner)->postJson(
            "/api/comments",
            $expected,
        );

        $response
            ->assertStatus(201)
            ->assertJson(
                $expected,
            );

        $this->assertDatabaseHas(
            app(Comment::class)->getTable(),
            $expected,
        );
    }

}
