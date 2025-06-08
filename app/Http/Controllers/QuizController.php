<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StartQuizRequest;

class QuizController extends Controller
{
    public function index()
    {
        return view("welcome");
    }

    public function start(StartQuizRequest $request)
    {
        $data = $request->validated();
        // pass the data to the service

        // get the questions from the api

        // redirect to the quiz
    }

    public function quiz()
    {

    }

    public function results()
    {

    }
}
