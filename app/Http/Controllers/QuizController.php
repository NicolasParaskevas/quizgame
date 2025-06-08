<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StartQuizRequest;
use App\Services\OpenTriviaService;
use App\Models\Search;

class QuizController extends Controller
{
    private OpenTriviaService $triviaService;

    public function __construct(OpenTriviaService $triviaService)
    {
        $this->triviaService = $triviaService;
    }

    public function index()
    {
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

        $quiz = $response["quiz"];
        $filtered = $results
            ->reject(function($q) {
                return $q["category"] === "Entertainment: Video Games";
            })
            ->sortBy("category")
            ->values();

        // add quiz in session
        
        return redirect("quiz");
    }

    public function quiz()
    {
        $quiz = session("quiz");

        if (empty($quizz))
        {
            abort(404);
        }

        return view("", $quiz);
    }

    
    public function answer()
    {
        
    }

    public function results()
    {

    }
}
