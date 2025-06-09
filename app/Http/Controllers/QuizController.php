<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubmitAnswerRequest;

class QuizController extends Controller
{
    public function index(int $index)
    {
        $total = session("total");

        $back = $index - 1;

        if ($index === 0)
        {
            $back = null;
        }

        $next = $index + 1;

        if ($index === ($total - 1))
        {
            $next = "/results";
        }
        
        return view("quiz", [
            "index" => $index,
            "total" => $total,
            "next"  => $next,
            "back"  => $back
        ]);
    }

    public function question(int $index)
    {
        $questions = session("questions");

        if (!isset($questions[$index]) || empty($questions[$index]))
        {
            return response()->json([
                "error" => "Question not found"
            ], 404);
        }

        $q = $questions[$index];

        $answers = collect($q['incorrect_answers'])
            ->push($q['correct_answer'])
            ->shuffle();
        
        return response()->json([
            "question" => $q["question"],
            "answers"  => $answers,
            "index"    => $index
        ]);
    }

    public function answer(SubmitAnswerRequest $request)
    {
        $data = $request->validated();
        $index = $data["index"];
        
        $answers = session("answers");
        $answers[$index] = [
            "question"       => $data["question"],
            "answer"         => $data["answer"],
            "correct_answer" => $data["correct_answer"]
        ];

        session("answers", $answers);

        $index++;
        return redirect()->to("quiz", $index);
    }

    public function results()
    {
        $userAnswers = session("answers");
        dd($userAnswers);
    }
}
