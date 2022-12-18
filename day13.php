<?php

include('common.php');
$input = getInput();

print "Part 1: " . getCorrectPairs($input) . "\n";
print "Part 2: " . sortPackets($input) . "\n";

function getCorrectPairs(array $input): int
{
    $indexSum = 0;
    $input = array_values($input);
    $pairsCount = ceil(count($input)/2);
    for ($index = 1; $index <= $pairsCount; $index++) {
        $left = $input[($index-1)*2];
        $right = $input[($index-1)*2+1];

        if (isOrderCorrect($left,$right)) {
            $indexSum += $index;
        }
    }

    return $indexSum;
}

function sortPackets(array $input): int
{
    $divider1 = [[2]];
    $divider2 = [[6]];

    $packets = [];
    foreach ($input as $line) {
        eval('$line = '.$line.';');
        $packets[] = $line;
    }
    $packets[] = $divider1;
    $packets[] = $divider2;
    usort($packets, 'compare');

    $index1 = $index2 = 0;
    foreach ($packets as $index => $packet) {
        if ($packet === $divider1) {
            $index1 = $index + 1;
        }

        if ($packet === $divider2) {
            $index2 = $index + 1;
        }
    }

    return $index1 * $index2;
}

function isOrderCorrect(string $left, string $right): bool
{
    eval('$left = '.$left.';');
    eval('$right = '.$right.';');

    $compare = compare($left, $right);
    // -1 and 0 mean the order is correct
    return $compare < 1;
}

function compare($left, $right): int
{
    if (is_array($left)) {
        if (is_array($right)) {
            return compareLists($left, $right);
        } else {
            return compareLists($left, [$right]);
        }
    } else {
        if (is_array($right)) {
            return compareLists([$left], $right);
        } else {
            return compareIntegers($left, $right);
        }
    }
}

function compareIntegers(int $left, int $right): int
{
    return $left <=> $right;
}

function compareLists(array $left, array $right): int
{
    $limit = max(count($left), count($right));
    for ($i = 0; $i < $limit; $i++) {
        if (!array_key_exists($i, $left)) {
            return -1;
        }

        if (!array_key_exists($i, $right)) {
            return 1;
        }

        $compare = compare($left[$i], $right[$i]);
        if ($compare !== 0) {
            return $compare;
        }
    }

    return 0;
}