<?php

include('common.php');

$input = getInput();
print "Part 1: " . getStart($input, 4) . "\n";
print "Part 2: " . getStart($input, 14) . "\n";

function getStart(array $input, int $markerLength): int
{
    $buffer = str_split($input[0]);
    $length = count($buffer);
    for ($start = 0; $start < $length; $start++) {
        $part = array_slice($buffer, $start, $markerLength);
        if (count(array_unique($part)) === $markerLength) {
            return $start + $markerLength;
        }
    }

    return 0;
}
