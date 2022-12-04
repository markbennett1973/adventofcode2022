<?php

include('common.php');
$input = getInput();
print "Part 1: " . getContains($input) . "\n";
print "Part 2: " . getOverlaps($input) . "\n";

function getContains(array $lines): int
{
    $contains = 0;
    foreach ($lines as $line) {
        list($a, $b) = explode(',', $line);
        if (fullyContains($a, $b) || fullyContains($b, $a)) {
            $contains++;
        }
    }

    return $contains;
}

function fullyContains(string $range1, string $range2): bool
{
    list ($aMin, $aMax) = explode('-', $range1);
    list ($bMin, $bMax) = explode('-', $range2);

    if ($aMin <= $bMin && $aMax >= $bMax) {
        return true;
    }

    return false;
}

function getOverlaps(array $lines): int
{
    $contains = 0;
    foreach ($lines as $line) {
        list($a, $b) = explode(',', $line);
        if (overlaps($a, $b)) {
            $contains++;
        }
    }

    return $contains;
}

function overlaps(string $range1, $range2): bool
{
    list ($aMin, $aMax) = explode('-', $range1);
    list ($bMin, $bMax) = explode('-', $range2);

    if ($aMax < $bMin || $bMax < $aMin) {
        return false;
    }

    return true;
}