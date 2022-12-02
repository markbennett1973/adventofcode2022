<?php

include('common.php');
$input = getInput(false);
print "Part 1: " . getMax($input) . "\n";
print "Part 2: " . getTopThree($input) . "\n";

function getMax(array $input): int
{
    $max = $current = 0;
    foreach ($input as $line) {
        if (is_numeric($line)) {
            $current += $line;
        } else {
            $max = max($current, $max);
            $current = 0;
        }
    }

    return $max;
}

function getTopThree(array $input): int
{
    $index = $current = 0;
    $totals = [];
    foreach ($input as $line) {
        if (is_numeric($line)) {
            $current += $line;
        } else {
            $totals[$index] = $current;
            $index++;
            $current = 0;
        }
    }

    rsort($totals);
    return array_sum(array_slice($totals, 0, 3));
}