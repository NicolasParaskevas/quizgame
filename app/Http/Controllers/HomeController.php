<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StartQuizRequest;
use App\Services\OpenTriviaService;
use App\Services\QuestionService;
use App\Models\Search;

class HomeController extends Controller
{
    private OpenTriviaService $triviaService;
    private QuestionService $questionService;

    public function __construct(OpenTriviaService $triviaService, QuestionService $questionService)
    {
        $this->triviaService = $triviaService;
        $this->questionService = $questionService;
    }

    public function index()
    {
        $this->questionService->forget();
        return view("welcome");
    }

    public function start(StartQuizRequest $request)
    {
        $data = $request->validated();
        $entry = Search::create($data);

        $response = $this->triviaService->fetch($entry);

        if ($response["error"] !== null)
        {
            return redirect()->back()->withErrors([
                "api_error" => $response["error"]
            ]);
        }

        $filtered = $response["quiz"]
            ->reject(function($q) {
                return $q["category"] === "Entertainment: Video Games";
            })
            ->sortBy("category")
            ->values();

        $this->questionService->load($filtered);
        
        return redirect()->route('quiz');
    }
}
