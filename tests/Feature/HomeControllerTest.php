<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\Search;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\OpenTriviaService;
use App\Services\QuestionService;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_clears_session_and_shows_welcome()
    {
        session(['questions' => ['dummy']]);

        $questionService = Mockery::mock(QuestionService::class);
        $questionService->shouldReceive('forget')->once();

        $this->app->instance(QuestionService::class, $questionService);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    public function test_start_stores_search_and_loads_filtered_questions()
    {
        $input = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'questions' => 3,
            'difficulty' => 'easy',
            'type' => 'multiple'
        ];

        $mockResponse = [
            'error' => null,
            'quiz' => collect([
                [
                    'question' => 'What is 2+2?',
                    'category' => 'Math',
                    'correct_answer' => '4',
                    'incorrect_answers' => ['1', '2', '3']
                ],
                [
                    'question' => 'Who is Mario?',
                    'category' => 'Entertainment: Video Games',
                    'correct_answer' => 'A plumber',
                    'incorrect_answers' => ['Chef', 'Builder', 'Pilot']
                ]
            ])
        ];

        $triviaService = Mockery::mock(OpenTriviaService::class);
        $triviaService->shouldReceive('fetch')->once()->andReturn($mockResponse);
        $this->app->instance(OpenTriviaService::class, $triviaService);

        $questionService = Mockery::mock(QuestionService::class);
        $questionService->shouldReceive('load')->once()->with(Mockery::on(function ($collection) {
            return $collection instanceof Collection && $collection->count() === 1;
        }));
        $this->app->instance(QuestionService::class, $questionService);

        $response = $this->post('/start-quiz', $input);

        $response->assertRedirect(route('quiz'));
        $this->assertDatabaseHas('search_history', ['email' => 'john@example.com']);
    }

    public function test_start_redirects_back_with_error_on_api_failure()
    {
        $input = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'questions' => 5,
            'difficulty' => 'medium',
            'type' => 'boolean'
        ];

        $mockResponse = [
            'error' => 'API is unavailable',
            'quiz' => collect()
        ];

        $triviaService = Mockery::mock(OpenTriviaService::class);
        $triviaService->shouldReceive('fetch')->once()->andReturn($mockResponse);
        $this->app->instance(OpenTriviaService::class, $triviaService);

        $questionService = Mockery::mock(QuestionService::class);
        $questionService->shouldNotReceive('load');
        $this->app->instance(QuestionService::class, $questionService);

        $response = $this->from('/')->post('/start-quiz', $input);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['api_error' => 'API is unavailable']);
        $this->assertDatabaseHas('search_history', ['email' => 'jane@example.com']);
    }
}
