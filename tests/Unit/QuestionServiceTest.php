<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\QuestionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class QuestionServiceTest extends TestCase
{
    protected QuestionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new QuestionService();
        Session::start();
    }

    public function test_load_stores_data_in_session()
    {
        $questions = collect([
            [
                'question' => 'What is 2+2?',
                'correct_answer' => '4',
                'incorrect_answers' => ['3', '5', '6']
            ],
            [
                'question' => 'Capital of France?',
                'correct_answer' => 'Paris',
                'incorrect_answers' => ['Berlin', 'Rome', 'Madrid']
            ]
        ]);

        $this->service->load($questions);

        $this->assertEquals($questions->toArray(), session('questions'));
        $this->assertEquals([], session('user_answers'));
        $this->assertEquals(0, session('index'));
        $this->assertEquals(2, session('total'));
        $this->assertEquals(1, session('cap'));
    }

    public function test_forget_clears_session_data()
    {
        session([
            'questions' => ['dummy'],
            'user_answers' => ['dummy'],
            'index' => 1,
            'total' => 10,
            'cap' => 9
        ]);

        $this->service->forget();

        $this->assertNull(session('questions'));
        $this->assertNull(session('user_answers'));
        $this->assertNull(session('index'));
        $this->assertNull(session('total'));
        $this->assertNull(session('cap'));
    }

    public function test_set_and_get_user_answer()
    {
        session(['user_answers' => []]);

        $this->service->setUserAnswer(0, ['answer' => '4']);

        $this->assertEquals([
            0 => ['answer' => '4']
        ], session('user_answers'));
    }

    public function test_get_question_returns_correct_data()
    {
        $questions = [
            ['question' => 'What is 2+2?', 'correct_answer' => '4', 'incorrect_answers' => ['1', '2', '3']]
        ];
        session(['questions' => $questions]);

        $result = $this->service->getQuestion(0);

        $this->assertEquals($questions[0], $result);
    }

    public function test_get_question_returns_false_if_invalid()
    {
        session(['questions' => []]);

        $result = $this->service->getQuestion(0);

        $this->assertFalse($result);
    }

    public function test_get_answers_returns_shuffled_collection()
    {
        $question = [
            'question' => 'Capital of Spain?',
            'correct_answer' => 'Madrid',
            'incorrect_answers' => ['Barcelona', 'Seville', 'Valencia']
        ];
        session(['questions' => [$question]]);

        $answers = $this->service->getAnswers(0);

        $this->assertInstanceOf(Collection::class, $answers);
        $this->assertCount(4, $answers);
        $this->assertContains('Madrid', $answers);
    }

    public function test_get_correct_answer_returns_value()
    {
        $question = [
            'question' => '2+2?',
            'correct_answer' => '4',
            'incorrect_answers' => ['1', '2', '3']
        ];
        session(['questions' => [$question]]);

        $this->assertEquals('4', $this->service->getCorrectAnswer(0));
    }

    public function test_get_correct_answer_returns_false_if_question_missing()
    {
        session(['questions' => []]);

        $this->assertFalse($this->service->getCorrectAnswer(0));
    }

    public function test_get_total_and_cap_return_expected_values()
    {
        session(['total' => 10, 'cap' => 9]);

        $this->assertEquals(10, $this->service->getTotal());
        $this->assertEquals(9, $this->service->getCap());
    }
}
