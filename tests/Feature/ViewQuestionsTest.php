<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionsTest extends TestCase
{
    public function testUsersCanViewQuestions()
    {
        // 在 phpunit 中抛出异常
//        $this->withoutExceptionHandling();
         // 1. 假设 /questions 路由存在
         // 2. 访问链接 /questions
        $test = $this->get('/questions');
         // 3. 正常返回200
        $test->assertStatus(200);

    }
}
