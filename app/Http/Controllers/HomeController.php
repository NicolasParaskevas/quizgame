<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StartQuizRequest;
use App\Services\OpenTriviaService;
use App\Models\Search;

class HomeController extends Controller
{
    private OpenTriviaService $triviaService;

    public function __construct(OpenTriviaService $triviaService)
    {
        $this->triviaService = $triviaService;
    }

    public function index()
    {
        session()->forget("questions");
        session()->forget("answers");
        session()->forget("index");
        session()->forget("total");
        session()->forget("cap");

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

        session([
            'questions' => $filtered->toArray(),
            'answers'   => [],
            'index'     => 0,
            'total'     => $filtered->count(),
            'cap'       => ($filtered->count() - 1)
        ]);
        
        return redirect()->route('quiz');
    }
}
