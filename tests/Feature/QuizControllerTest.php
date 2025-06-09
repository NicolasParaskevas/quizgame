<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;

class QuizControllerTest extends TestCase
{
    private array $questions;

    protected function setUp(): void
    {
        parent::setUp();

        Session::start();

        $this->questions = [
            [
                'question' => 'What is 2+2?',
                'category' => 'Math',
                'correct_answer' => '4',
                'incorrect_answers' => ['1', '2', '3']
            ],
            [
                'question' => 'Capital of France?',
                'category' => 'Geography',
                'correct_answer' => 'Paris',
                'incorrect_answers' => ['Berlin', 'Rome', 'Madrid']
            ]
        ];

        session([
            'questions' => $this->questions,
            'user_answers' => [],
            'index' => 0,
            'total' => count($this->questions),
            'cap' => count($this->questions) - 1,
        ]);
    }

    public function test_quiz_page_loads()
    {
        $response = $this->get('/quiz');

        $response->assertStatus(200);
        $response->assertViewIs('quiz');
        $response->assertViewHas('total', 2);
    }

    public function test_question_endpoint_returns_valid_json()
    {
        $response = $this->getJson('/question/0');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'question',
            'category',
            'answers'
        ]);
        $this->assertEquals('What is 2+2?', $response['question']);
    }

    public function test_question_endpoint_returns_404_for_invalid_index()
    {
        $response = $this->getJson('/question/99');

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Question not found'
        ]);
    }

    public function test_post_answer_and_redirect_on_last_question()
    {
        $response = $this->postJson('/answer', [
            'question' => 'Capital of France?',
            'answer' => 'Paris',
            'index' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['redirect' => true]);

        $answers = session('user_answers');
        $this->assertEquals('Paris', $answers[1]['answer']);
        $this->assertEquals('Paris', $answers[1]['correct_answer']);
    }

    public function test_post_answer_without_redirect_if_not_last()
    {
        $response = $this->postJson('/answer', [
            'question' => 'What is 2+2?',
            'answer' => '4',
            'index' => 0,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['redirect' => false]);
    }

    public function test_results_dumps_user_answers()
    {
        session(['user_answers' => [
            0 => ['question' => 'What is 2+2?', 'answer' => '4', 'correct_answer' => '4'],
            1 => ['question' => 'Capital of France?', 'answer' => 'Paris', 'correct_answer' => 'Paris'],
        ]]);

        $this->expectOutputRegex('/Capital of France\?/');

        $this->get('/results');
    }
}
