<?php

namespace App\Services;

use App\Models\Search;
use Illuminate\Support\Facades\Http;

class OpenTriviaService
{
    const string API_URL = "https://opentdb.com/api.php";
    private array $response;

    public function __construct()
    {
        $this->response = array(
            "error" => null,
            "quiz" => collect()
        );
    }

    public function fetch(Search $model)
    {
        $params = [
            "amount"     => $model->questions,
            "difficulty" => $model->difficulty
        ];

        if ($model->type !== null) {
            $params["type"] = $model->type;
        }

        $resp = Http::get(self::API_URL, $params);

        if ($resp->successful() && $resp["response_code"] === 0)
        {
            $this->response["quiz"] = collect($resp->json()['results']);
            return $this->response;
        }

        $this->response["error"] = "There was an error in fetching Open Trivia API";
        return $this->response;
    }
}