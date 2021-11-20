<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Memo;
use \Symfony\Component\HttpFoundation\Response;

class MemoControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param int $count
     * @param array|array $param
     * @return array{user: User, memos: Collection<Memo>}

     */
    private function createMemosAndUser(int $count, array $param = []): array {
        $user = User::factory()->create();
        $memos = Memo::factory()
            ->for($user, 'author')
            ->count($count)->create(
                $param,
            );

        return [
            'user' => $user,
            'memos' => $memos,
        ];
    }

    /** @test */
    public function memo一覧が取得できる()
    {
        ['user' => $user, 'memos' => $memos] = $this->createMemosAndUser(3, ['is_public' => false]);
        $response = $this->actingAs($user)->getJson('/api/memos');
        $response
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJson($memos->map(function(Memo $memo) {
                return [
                    'title' => $memo->title,
                    'body' => $memo->body,
                    'status' => $memo->status,
                    'is_public' => $memo->is_public,
                    'author_id' => $memo->author->id,
                ];
            })->toArray());

        $memos->each(function ($memo) {
            $expected = [
                'title' => $memo->title,
                'body' => $memo->body,
                'status' => $memo->status,
                'is_public' => $memo->is_public,
                'author_id' => $memo->author->id,
            ];

            $this->assertDatabaseHas(
                app(Memo::class)->getTable(),
                $expected,
            );
        });
    }

    /** @test */
    public function memoがid指定で取得できる()
    {
        ['user' => $user, 'memos' => $memos] = $this->createMemosAndUser(3);
        $response = $this->actingAs($user)->getJson("/api/memos/{$memos[0]->id}");
        $response
            ->assertStatus(200);
        $response
            ->assertJson(function (AssertableJson $json) use ($memos) {
                $json->has('data')
                    ->where('data.id', $memos[0]->id)
                    ->where('data.title', $memos[0]->title)
                    ->where('data.body', $memos[0]->body)
                    ->where('data.status', $memos[0]->status)
                    ->where('data.is_public', $memos[0]->is_public)
                    ->where('data.author_id', $memos[0]->author_id);
            });
    }

    /** @test */
    public function memo取得時にidが存在しない場合()
    {
        ['user' => $user, 'memos' => $memos] = $this->createMemosAndUser(3);
        $count = Memo::all()->count();
        $invalid_id = $count + 1;

        $response = $this->actingAs($user)->getJson("/api/memos/{$invalid_id}");
        $response
            ->assertStatus(404);
    }

    /** @test */
    public function memoのid指定_非認証()
    {
        ['user' => $user, 'memos' => $memos] = $this->createMemosAndUser(3);
        $response = $this->getJson("/api/memos/{$memos[0]->id}");
        $response
            ->assertStatus(401);
    }

    /** @test */
    public function privateなmemoのid指定_他人のmemoは見れない()
    {
        ['user' => $user, 'memos' => $memos] = $this->createMemosAndUser(3, ['is_public' => false]);
        $user2 = User::factory()->create();
        $response = $this->actingAs($user2)->getJson("/api/memos/{$memos[0]->id}");
        $response
            ->assertStatus(403);
    }

    /** @test */
    public function publicなmemoのid指定_他人のmemoは見ることができる()
    {
        ['user' => $user, 'memos' => $memos] = $this->createMemosAndUser(3, ['is_public' => true]);
        $user2 = User::factory()->create();
        $response = $this->actingAs($user2)->getJson("/api/memos/{$memos[0]->id}");
        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($memos) {
                $json->has('data')
                    ->where('data.id', $memos[0]->id)
                    ->where('data.title', $memos[0]->title)
                    ->where('data.body', $memos[0]->body)
                    ->where('data.status', $memos[0]->status)
                    ->where('data.is_public', $memos[0]->is_public)
                    ->where('data.author_id', $memos[0]->author_id);
            });
    }

    /** @test */
    public function memoをstoreできる()
    {
        $expected = [
            'title' => 'tttt',
            'body' => 'bbbb',
            'status' => Memo::PENDING,
        ];

        ['user' => $user, 'memos' => $memos] = $this->createMemosAndUser(3, ['is_public' => true]);
        $response = $this->actingAs($user)->postJson(
            "/api/memos/",
            $expected,
        );
        $response
            ->assertStatus(201)
            ->assertJson(
                $expected,
            );

        $this->assertDatabaseHas(
            app(Memo::class)->getTable(),
            $expected,
        );
    }

    /** @test */
    public function memoがid指定で更新できる()
    {
        $post_data = [
            'title' => 'tttt',
            'body' => 'bbbb',
            'status' => Memo::PENDING,
        ];

        $count = 3;
        ['user' => $user, 'memos' => $memos] = $this->createMemosAndUser($count, ['status' => MEMO::DONE]);

        // update
        $response = $this
            ->actingAs($user)
            ->patchJson(
                "/api/memos/{$memos[$count - 1]->id}",
                $post_data,
            );

        $response->assertStatus(200);
        $this->assertSame(
            true, $response['status'],
        );

        // get
        $response = $this
            ->actingAs($user)
            ->getJson("/api/memos/{$memos[$count - 1]->id}");
        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($post_data, $count) {
                $json->has('data')
                    ->where('data.title', $post_data['title'])
                    ->where('data.body', $post_data['body'])
                    ->where('data.status', $post_data['status'])
                    ->etc();
            });
    }

    /** @test */
    public function memoがid指定でDeleteできる()
    {
        $count = 3;
        ['user' => $user, 'memos' => $memos] = $this->createMemosAndUser($count);

        // delete
        $response = $this
            ->actingAs($user)
            ->deleteJson("/api/memos/{$memos[0]->id}");
        $response
            ->assertStatus(200);

        // confirm delete
        $this->assertDeleted($memos[0]);
    }

    /** @test */
    public function 別ユーザのmemoは削除できない()
    {
        $count = 3;
        ['user' => $user1, 'memos' => $memos] = $this->createMemosAndUser($count);
        $user2 = User::factory()->create();

        // delete as another user (the user who doesn't have its memo)
        $response = $this
            ->actingAs($user2)
            ->deleteJson("/api/memos/{$memos[0]->id}");
        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
