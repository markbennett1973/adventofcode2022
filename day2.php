<?php

include('common.php');

const ROCK = 1;
const PAPER = 2;
const SCISSORS = 3;

$input = getInput();
print "Part 1: " . getScore($input) . "\n";
print "Part 2: " . getScore2($input) . "\n";

function getScore(array $turns): int
{
    $score = 0;
    foreach ($turns as $turn) {
        $score += getScoreForTurn($turn[0], $turn[2]);
    }

    return $score;
}

function getScore2(array $turns): int
{
    $score = 0;
    foreach ($turns as $turn) {
        $myChoice = getMyChoice($turn[0], $turn[2]);
        $myChoice = convertMyChoice($myChoice);
        $score += getScoreForTurn($turn[0], $myChoice);
    }

    return $score;
}

function getScoreForTurn(string $opponentChoice, string $myChoice): int
{
    $opponentChoice = getChoice($opponentChoice);
    $myChoice = getChoice($myChoice);
    $gameScore = getGameScore($opponentChoice, $myChoice);

    return $gameScore + $myChoice;
}

function getChoice(string $choice): int
{
    return match ($choice) {
        'A', 'X' => ROCK,
        'B', 'Y' => PAPER,
        'C', 'Z' => SCISSORS,
        default => 0,
    };
}

function getGameScore(int $opponentChoice, int $myChoice): int
{
    switch ($opponentChoice) {
        case ROCK:
            switch ($myChoice) {
                case ROCK:
                    return 3;
                case PAPER:
                    return 6;
                case SCISSORS:
                    return 0;
            }
            break;
        case PAPER:
            switch ($myChoice) {
                case ROCK:
                    return 0;
                case PAPER:
                    return 3;
                case SCISSORS:
                    return 6;
            }
            break;
        case SCISSORS:
            switch ($myChoice) {
                case ROCK:
                    return 6;
                case PAPER:
                    return 0;
                case SCISSORS:
                    return 3;
            }
            break;
    }
    return 0;
}

function getMyChoice(string $opponentChoice, string $target): int
{
    $opponentChoice = getChoice($opponentChoice);
    switch($opponentChoice) {
        case ROCK:
            switch ($target) {
                case 'X':
                  return SCISSORS;
                case 'Y':
                    return ROCK;
                case 'Z':
                    return PAPER;
            }
            break;

        case PAPER:
            switch ($target) {
                case 'X':
                    return ROCK;
                case 'Y':
                    return PAPER;
                case 'Z':
                    return SCISSORS;
            }
            break;

        case SCISSORS:
            switch ($target) {
                case 'X':
                    return PAPER;
                case 'Y':
                    return SCISSORS;
                case 'Z':
                    return ROCK;
            }
            break;
    }

    return 0;
}

function convertMyChoice(int $myChoice): string
{
    return match ($myChoice) {
        ROCK => 'X',
        PAPER => 'Y',
        SCISSORS => 'Z',
        default => '',
    };
}