<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Memo;

class MemoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function memo一覧が取得できる()
    {
        $memos = Memo::factory(Memo::class)->count(3)->create();
        $response = $this->getJson(route('memo.all'));
        $response
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJson($memos->jsonSerialize());

        $memos->each(function ($memo) {
            $expected = [
                'title' => $memo->title,
                'body' => $memo->body,
                'status' => $memo->status,
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
        $memos = Memo::factory(Memo::class)->count(3)->create();

        $response = $this->getJson(route('memo.get', [
            'id' => $memos[0]->id,
        ]));
        $response
            ->assertStatus(200)
            ->assertJson(
                $memos[0]->jsonSerialize()
            );
    }

    /** @test */
    public function memo取得時にidが存在しない場合()
    {
        $memos = Memo::factory(Memo::class)->count(3)->create();
        $count = Memo::all()->count();

        $response = $this->getJson(route('memo.get', [
            'id' => $count + 1,
        ]));
        $response
            ->assertStatus(404);
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
        $memos = Memo::factory(Memo::class)->count($count)->create();

        # update
        $response = $this->patchJson(
            route('memo.update', [
            'id' => $memos[$count - 1]->id,
            ]),
            $post_data,
        );
        $response
            ->assertStatus(200);
        $this->assertSame(
            1, $response['status'],
        );

        # get
        $response = $this->getJson(route('memo.get', [
            'id' => $memos[$count - 1]->id,
        ]));
        $response
            ->assertStatus(200)
            ->assertJson(
                $post_data,
            );
    }

    /** @test */
    public function memoがid指定でDeleteできる()
    {
        $count = 3;
        $memos = Memo::factory(Memo::class)->count($count)->create();

        # delete
        $response = $this->deleteJson(
            route('memo.update', [
                'id' => $memos[0]->id,
            ]));
        $response
            ->assertStatus(200);


        # get
        $response = $this->getJson(route('memo.all'));
        $response
            ->assertStatus(200)
            ->assertJsonCount($count-1);
    }

}
