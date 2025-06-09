<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubmitAnswerRequest;
use App\Services\QuestionService;

class QuizController extends Controller
{
    private QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index()
    {
        $total = $this->questionService->getTotal();
        return view("quiz", [
            "total" => $total
        ]);
    }

    public function question(int $index)
    {
        $q = $this->questionService->getQuestion($index);

        if ($q === false)
        {
            return response()->json([
                "error" => "Question not found"
            ], 404);
        }

        $answers = $this->questionService->getAnswers($index);

        return response()->json([
            "question" => $q["question"],
            "category" => $q["category"],
            "answers"  => $answers
        ]);
    }

    public function answer(SubmitAnswerRequest $request)
    {
        $data = $request->validated();
        $index = (int)$data["index"];
        
        $correct_answer = $this->questionService->getCorrectAnswer($index);

        $answer = null;
        if (isset($data["answer"]))
        {
            $answer = $data["answer"];
        }

        $this->questionService->setUserAnswer($index, [
            "question"       => $data["question"],
            "answer"         => $answer,
            "correct_answer" => $correct_answer
        ]);

        // if we reached the end, then redirect to results page
        $redirect = false;
        if ($index === $this->questionService->getCap())
        {
            $redirect = true;
        }

        return response()->json([
            "redirect" => $redirect
        ], 200);
    }

    public function results()
    {
        $userAnswers = $this->questionService->getUserAnswers();
        dd($userAnswers);
    }
}
