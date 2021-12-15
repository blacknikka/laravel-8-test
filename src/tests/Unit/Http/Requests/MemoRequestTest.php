<?php

namespace Tests\Unit\Http\Requests;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Http\Requests\MemoRequest;
use Illuminate\Support\Facades\Validator;

class MemoRequestTest extends TestCase
{
    /**
     * 正常系
     *
     * @test
     * @dataProvider memoDataProvider
     */
    public function ルールの確認($title, $body, $status, $expected)
    {
        // test target
        $sut = new MemoRequest();

        $rules = $sut->rules();
        $validator = Validator::make([
            "title" => $title,
            "body" => $body,
            "status" => $status,
        ], $rules);

        $this->assertEquals($validator->passes(), $expected);
    }

    /**
     * 余計なデータが有る場合
     *
     * @test
     */
    public function 余計なデータが存在する場合()
    {
        // test target
        $sut = new MemoRequest();

        $rules = $sut->rules();
        $validator = Validator::make([
            "id" => 5,
            "title" => "title",
            "body" => "body",
            "status" => "pending",
        ], $rules);

        $this->assertTrue($validator->passes());
    }

    public function memoDataProvider(): array {
        return [
            ["title", "body", "done", true],
            ["title", "body", "pending", true],
            ["title", "body", "doing", true],
            [null, "body", "done", false],
            ["title", null, "done", false],
            ["title", "body", "foo", false],       // status is invalid
            [1, "body", "done", false],
            ["title", 1, "done", false],
        ];
    }
}
