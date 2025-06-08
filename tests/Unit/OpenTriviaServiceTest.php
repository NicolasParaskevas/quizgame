<?php

namespace Tests\Unit;

use App\Models\Search;
use App\Services\OpenTriviaService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenTriviaServiceTest extends TestCase
{
    public function test_fetch_returns_quiz_on_success()
    {
        Http::fake([
            'https://opentdb.com/api.php*' => Http::response([
                'response_code' => 0,
                'results' => [
                    [
                        'question' => 'What is 2+2?',
                        'category' => 'Math',
                        'correct_answer' => '4',
                        'incorrect_answers' => [
                            '3',
                            '5',
                            '1'
                        ]
                    ],
                    [
                        'question' => 'What is the capital of France?',
                        'category' => 'Geography',
                        'correct_answer' => 'Paris',
                        'incorrect_answers' => [
                            'Athens',
                            'Nicosia',
                            'Lisbon'
                        ]
                    ],
                    [
                        'question' => 'Will Nicolas get the job?',
                        'category' => 'Interview',
                        'correct_answer' => 'True',
                        'incorrect_answers' => [
                            'False'
                        ]
                    ],
                ]
            ], 200)
        ]);

        $search = Search::factory()->make([
            'questions' => 3,
            'difficulty' => 'easy',
            'type' => 'multiple',
        ]);

        $service = new OpenTriviaService();
        $result = $service->fetch($search);

        $this->assertNull($result['error']);
        $this->assertCount(3, $result['quiz']);
        $this->assertEquals('Math', $result['quiz'][0]['category']);
    }

    public function test_fetch_returns_error_on_failed_request()
    {
        Http::fake([
            'https://opentdb.com/api.php*' => Http::response([], 500)
        ]);

        $search = Search::factory()->make([
            'questions' => 10,
            'difficulty' => 'hard',
            'type' => null,
        ]);

        $service = new OpenTriviaService();
        $result = $service->fetch($search);

        $this->assertNotNull($result['error']);
        $this->assertEmpty($result['quiz']);
    }

    public function test_fetch_handles_nonzero_response_code()
    {
        Http::fake([
            'https://opentdb.com/api.php*' => Http::response([
                'response_code' => 1,
                'results' => []
            ], 200)
        ]);

        $search = Search::factory()->make([
            'questions' => 5,
            'difficulty' => 'medium',
            'type' => 'boolean',
        ]);

        $service = new OpenTriviaService();
        $result = $service->fetch($search);

        $this->assertNotNull($result['error']);
        $this->assertEmpty($result['quiz']);
    }
}
