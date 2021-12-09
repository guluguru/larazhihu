<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostAnswersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_post_an_answer_to_a_published_question()
    {
//        1. 假设存在问题
        $questions = Question::factory()->published()->create();
        $user = User::factory()->create();
//        2. 访问路由
        $response = $this->post("/questions/{$questions->id}/answers", [
            'user_id'=>$user->id,
            'content'=>'This is an answer.',
        ]);
//        3. 看到问题
        $response->assertStatus(201); // 201 Created，该请求已成功，并因此创建了一个新的资源。这通常是在POST请求，或是某些PUT请求之后返回的响应。
        $answer = $questions->answers()->where('user_id', $user->id)->first();
        $this->assertNotNull($answer);
        $this->assertEquals(1, $questions->answers()->count());
    }

    /** @test */
    public function can_not_post_an_answer_to_an_unpublished_question()
    {
        $question = Question::factory()->unpublished()->create();
        $user = User::factory()->create();

        $response = $this->withExceptionHandling()
            ->post("/questions/{$question->id}/answers", [
                'user_id' => $user->id,
                'content' => 'This is an answer.'
            ]);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('answers', ['question_id' => $question->id]);
        $this->assertEquals(0, $question->answers()->count());
    }
}
