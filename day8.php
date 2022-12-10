<?php

include('common.php');
$input = getInput();
$trees = getTrees($input);
$rows = count($trees);
$cols = count($trees[0]);

print "Part 1: " . countVisible($trees) . "\n";
print "Part 2: " . getMaxScenicScore($trees) . "\n";

function countVisible(array $trees): int
{
    global $rows, $cols;
    $visible = 0;
    for ($row = 0; $row < $rows; $row++) {
        for ($col = 0; $col < $cols; $col++) {
            if (isVisible($trees, $row, $col)) {
                $visible++;
            }
        }
    }

    return $visible;
}

function getTrees(array $lines): array
{
    $trees = [];
    foreach ($lines as $line) {
        $trees[] = str_split($line);
    }

    return $trees;
}

function isVisible(array $trees, int $row, int $col): bool
{
    global $rows, $cols;
    if ($row === 0 || $row === ($rows - 1)) {
        return true;
    }

    if ($col === 0 || $col === ($cols - 1)) {
        return true;
    }

    if (visibleFromLeft($trees, $row, $col)
        || visibleFromRight($trees, $row, $col)
        || visibleFromTop($trees, $row, $col)
        || visibleFromBottom($trees, $row, $col)) {
        return true;
    }

    return false;
}

function visibleFromLeft(array $trees, $row, $col): bool
{
    $currentTreeHeight = $trees[$row][$col];
    $visible = true;
    for ($i = 0; $i < $col; $i++) {
        if ($trees[$row][$i] >= $currentTreeHeight) {
            $visible = false;
        }
    }

    return $visible;
}

function visibleFromRight(array $trees, $row, $col): bool
{
    global $cols;
    $currentTreeHeight = $trees[$row][$col];
    $visible = true;
    for ($i = $col+1; $i < $cols; $i++) {
        if ($trees[$row][$i] >= $currentTreeHeight) {
            $visible = false;
        }
    }

    return $visible;
}

function visibleFromTop(array $trees, $row, $col): bool
{
    $currentTreeHeight = $trees[$row][$col];
    $visible = true;
    for ($i = 0; $i < $row; $i++) {
        if ($trees[$i][$col] >= $currentTreeHeight) {
            $visible = false;
        }
    }

    return $visible;
}

function visibleFromBottom(array $trees, $row, $col): bool
{
    global $rows;
    $currentTreeHeight = $trees[$row][$col];
    $visible = true;
    for ($i = $row+1; $i < $rows; $i++) {
        if ($trees[$i][$col] >= $currentTreeHeight) {
            $visible = false;
        }
    }

    return $visible;
}

function getMaxScenicScore(array $trees): int
{
    $maxScore = 0;
    foreach ($trees as $row => $cols) {
        foreach ($cols as $col => $height) {
            $score = getScenicScore($trees, $row, $col, $height);
            $maxScore = max($score, $maxScore);
        }
    }

    return $maxScore;
}

function getScenicScore(array $trees, int $row, int $col, int $currentHeight): int
{
    return getScoreLeft($trees, $row, $col, $currentHeight)
        * getScoreRight($trees, $row, $col, $currentHeight)
        * getScoreUp($trees, $row, $col, $currentHeight)
        * getScoreDown($trees, $row, $col, $currentHeight);
}

function getScoreUp(array $trees, int $row, int $col, int $currentHeight): int
{
    $score = 0;
    $row--;
    while (array_key_exists($row, $trees)) {
        $score++;
        if ($trees[$row][$col] >= $currentHeight) {
            return $score;
        }
        $row--;
    }

    return $score;
}

function getScoreDown(array $trees, int $row, int $col, int $currentHeight): int
{
    $score = 0;
    $row++;
    while (array_key_exists($row, $trees)) {
        $score++;
        if ($trees[$row][$col] >= $currentHeight) {
            return $score;
        }
        $row++;
    }

    return $score;
}

function getScoreLeft(array $trees, int $row, int $col, int $currentHeight): int
{
    $score = 0;
    $col--;
    while (array_key_exists($col, $trees[$row])) {
        $score++;
        if ($trees[$row][$col] >= $currentHeight) {
            return $score;
        }
        $col--;
    }

    return $score;
}

function getScoreRight(array $trees, int $row, int $col, int $currentHeight): int
{
    $score = 0;
    $col++;
    while (array_key_exists($col, $trees[$row])) {
        $score++;
        if ($trees[$row][$col] >= $currentHeight) {
            return $score;
        }
        $col++;
    }

    return $score;
}