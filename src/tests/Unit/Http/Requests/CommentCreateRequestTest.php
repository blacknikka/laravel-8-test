<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\CommentCreateRequest;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class CommentCreateRequestTest extends TestCase
{
    /**
     * 正常系
     *
     * @test
     * @dataProvider commentDataProvider
     */
    public function ルールの確認($nickname, $body, $memo_id, $expected)
    {
        // test target
        $sut = new CommentCreateRequest();

        $rules = $sut->rules();
        $validator = Validator::make([
            "nickname" => $nickname,
            "body" => $body,
            "memo_id" => $memo_id,
        ], $rules);

        $this->assertEquals($validator->passes(), $expected);
        $this->assertTrue(true);
    }

    public function commentDataProvider(): array {
        return [
            ["nickname", "body", 1, true],
            [null, "body", 1, false],
            ["nickname", null, 1, false],
            ["nickname", "body", null, false],
            [1, "body", 1, false],
            ["nickname", 1, 1, false],
            ["nickname", "body", 0, false],
            ["nickname", "body", -1, false],
            ["nickname", "body", "-1", false],
            ["nickname", "body", "aaa", false],
        ];
    }
}
