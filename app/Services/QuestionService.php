<?php

namespace App\Services;

use Illuminate\Support\Collection;

class QuestionService
{
    private const SESSION_QUESTIONS    = 'questions';
    private const SESSION_USER_ANSWERS = 'user_answers';
    private const SESSION_INDEX        = 'index';
    private const SESSION_TOTAL        = 'total';
    private const SESSION_CAP          = 'cap';

    public function load(Collection $questions): void
    {
        session([
            self::SESSION_QUESTIONS    => $questions->toArray(),
            self::SESSION_USER_ANSWERS => [],
            self::SESSION_INDEX        => 0,
            self::SESSION_TOTAL        => $questions->count(),
            self::SESSION_CAP          => $questions->count() - 1,
        ]);
    }

    public function forget(): void
    {
        session()->forget([
            self::SESSION_QUESTIONS,
            self::SESSION_USER_ANSWERS,
            self::SESSION_INDEX,
            self::SESSION_TOTAL,
            self::SESSION_CAP,
        ]);
    }

    public function setUserAnswer(int $index, array $answer): void
    {
        $answers = $this->getUserAnswers();
        $answers[$index] = $answer;

        session([self::SESSION_USER_ANSWERS => $answers]);
    }

    public function getQuestion(int $index): array|false
    {
        $questions = session(self::SESSION_QUESTIONS);

        if (!isset($questions[$index]) || empty($questions[$index])) {
            return false;
        }

        return $questions[$index];
    }

    public function getAnswers(int $index): Collection|false
    {
        $q = $this->getQuestion($index);
        if ($q === false) {
            return false;
        }

        return collect($q['incorrect_answers'])
            ->push($q['correct_answer'])
            ->shuffle();
    }

    public function getUserAnswers(): array
    {
        return session(self::SESSION_USER_ANSWERS);
    }

    public function getCorrectAnswer(int $index): string|false
    {
        $q = $this->getQuestion($index);

        if ($q === false) {
            return false;
        }

        return $q['correct_answer'];
    }

    public function getTotal(): int
    {
        return session(self::SESSION_TOTAL);
    }

    public function getCap(): int
    {
        return session(self::SESSION_CAP);
    }
}
