<?php

include('common.php');
$input = getInput();

print "Part 1: " . getFewestSteps($input) . "\n";
print "Part 2: " . findBestRoute($input) . "\n";

function getFewestSteps(array $input): int
{
    $map = array_map('str_split', $input);
    list ($startRow, $startCol, $goalRow, $goalCol) = findGoals($map);
    return countSteps($map, $startRow, $startCol, $goalRow, $goalCol, PHP_INT_MAX);
}

function findBestRoute(array $input): int
{
    $map = array_map('str_split', $input);
    list ($startRow, $startCol, $goalRow, $goalCol) = findGoals($map);
    $minSteps = PHP_INT_MAX;
    $startPoints = getStartPoints($map);
    foreach ($startPoints as $index => $startPoint) {
        $steps = countSteps($map, $startPoint[0], $startPoint[1], $goalRow, $goalCol, $minSteps);
        $minSteps = min($steps, $minSteps);
    }

    return $minSteps;
}

function getStartPoints(array $map): array
{
    $rows = count($map);
    $cols = count($map[0]);
    $startPoints = [];

    for ($row = 0; $row < $rows; $row++) {
        for ($col = 0; $col < $cols; $col++) {
            if ($map[$row][$col] === 'a') {
                $startPoints[] = [$row, $col];
            }
        }
    }

    return $startPoints;
}

function countSteps(array $map, int $startRow, int $startCol, int $goalRow, int $goalCol, int $limit): int
{
    $goalNode = "$goalRow,$goalCol";
    $visitedNodes = $currentNodes = ["$startRow,$startCol"];
    $steps = 0;
    while (!in_array($goalNode, $visitedNodes)) {
        $currentNodes = exploreMap($map, $currentNodes, $visitedNodes);
        $steps++;
        if ($steps > $limit) {
            return PHP_INT_MAX;
        }
    }

    return $steps;
}

function findGoals(array &$map): array
{
    $startRow = $startCol = $goalRow = $goalCol = -1;
    foreach ($map as $row => $cols) {
        foreach ($cols as $col => $value) {
            if ($map[$row][$col] === 'S') {
                $startRow = $row;
                $startCol = $col;
                $map[$row][$col] = 'a';
            }

            if ($map[$row][$col] === 'E') {
                $goalRow = $row;
                $goalCol = $col;
                $map[$row][$col] = 'z';
            }
        }
    }
    return [$startRow, $startCol, $goalRow, $goalCol];
}

function exploreMap(array $map, array $currentNodes, array &$visitedNodes): array
{
    $newNodes = [];
    foreach ($currentNodes as $currentNode) {
        list($row, $col) = array_map('intval', explode(',', $currentNode));
        $currentHeight = $map[$row][$col];
        $maxHeight = chr(ord($currentHeight) + 1);

        // check left
        if (array_key_exists($col-1, $map[$row])
            && $map[$row][$col-1] <= $maxHeight
            && !in_array($row.','.($col-1), $visitedNodes)) {
            $newNodes[] = $row.','.($col-1);
        }

        // check right
        if (array_key_exists($col+1, $map[$row])
            && $map[$row][$col+1] <= $maxHeight
            && !in_array($row.','.($col+1), $visitedNodes)) {
            $newNodes[] = $row.','.($col+1);
        }

        // check up
        if (array_key_exists($row-1, $map)
            && $map[$row-1][$col] <= $maxHeight
            && !in_array(($row-1).','.$col, $visitedNodes)) {
            $newNodes[] = ($row-1).','.$col;
        }

        // check down
        if (array_key_exists($row+1, $map)
            && $map[$row+1][$col] <= $maxHeight
            && !in_array(($row+1).','.$col, $visitedNodes)) {
            $newNodes[] = ($row+1).','.$col;
        }
    }

    $visitedNodes = array_unique(array_merge($visitedNodes, $newNodes));
    return array_unique($newNodes);
}