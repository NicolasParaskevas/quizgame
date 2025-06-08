<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StartQuizRequest;
use App\Services\OpenTriviaService;
use App\Models\Search;

class QuizController extends Controller
{
    public function index()
    {
        return view("quiz");
    }

    public function question(int $index)
    {
        $questions = session("questions");
        $total     = session("total");

        if (!isset($questions[$index]) || empty($questions[$index]))
        {
            abort(404);
        }

        $q = $questions[$index];
        $answers = collect($q['incorrect_answers'])
            ->push($q['correct_answer'])
            ->shuffle();
        
        $back = $index - 1;

        if ($index === 0)
        {
            $back = null;
        }

        $next = $index + 1;

        if ($index === (int)$total)
        {
            $next = "/results";
        }

        return response()->json([
            "quiz"    => $questions[$index],
            "asnwers" => $answers,
            "index"   => $index,
            "total"   => $total,
            "next"    => $next,
            "back"    => $back
        ]);
    }

    
    public function answer()
    {
        
    }

    public function results()
    {
        $userAnswers = session("answers");
        dd($userAnswers);
    }
}
