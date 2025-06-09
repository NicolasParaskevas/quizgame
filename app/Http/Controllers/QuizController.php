<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubmitAnswerRequest;

class QuizController extends Controller
{
    public function index()
    {
        $total = session("total");
        
        return view("quiz", [
            "total" => $total
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
            "answers"  => $answers
        ]);
    }

    public function answer(SubmitAnswerRequest $request)
    {
        $data = $request->validated();
        $index = $data["index"];
        
        $answers = session("answers");

        // find the correct answer
        $question = session("questions")[$index];

        $correct_answer = null;
        if (!empty($question)) 
        {
            $correct_answer = $question["correct_answer"];
        }

        $answers[$index] = [
            "question"       => $data["question"],
            "answer"         => $data["answer"],
            "correct_answer" => $correct_answer
        ];

        // if we reached the end, then redirect to results page
        $cap = session("cap");
        if ($index > $cap)
        {
            return redirect()->to("results");
        }

        session("answers", $answers);

        return response()->json(["ok"], 200);
    }

    public function results()
    {
        $userAnswers = session("answers");
        dd($userAnswers);
    }
}
