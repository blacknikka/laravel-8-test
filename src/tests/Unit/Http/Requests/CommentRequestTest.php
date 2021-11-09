<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\CommentRequest;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class CommentRequestTest extends TestCase
{
    /**
     * 正常系
     *
     * @test
     * @dataProvider commentDataProvider
     */
    public function ルールの確認($nickname, $body, $expected)
    {
        // test target
        $sut = new CommentRequest();

        $rules = $sut->rules();
        $validator = Validator::make([
            "nickname" => $nickname,
            "body" => $body,
        ], $rules);

        $this->assertEquals($validator->passes(), $expected);
        $this->assertTrue(true);
    }

    public function commentDataProvider(): array {
        return [
            ["nickname", "body", true],
            [null, "body", false],
            ["nickname", null, false],
            [1, "body", false],
            ["title", 1, false],
        ];
    }
}
