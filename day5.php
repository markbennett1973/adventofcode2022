<?php

include('common.php');

const CRANE_9000 = 0;
const CRANE_9001 = 1;

$input = getInput();
print "Part 1: " . getTopCrates($input, CRANE_9000) . "\n";
print "Part 2: " . getTopCrates($input, CRANE_9001) . "\n";

function getTopCrates(array $input, int $crane): string
{
    $crates = getCrates($input);
    foreach ($input as $line) {
        preg_match('/move (\d+) from (\d+) to (\d+)/', $line, $matches);
        if (count($matches) === 4) {
            list ($dummy, $numberToMove, $source, $target) = $matches;
            if (CRANE_9000 === $crane) {
                moveCrates9000($crates, $numberToMove, $source, $target);
            } else {
                moveCrates9001($crates, $numberToMove, $source, $target);
            }
        }
    }

    $output = '';
    for ($key = 1; $key <= count($crates); $key++) {
        $output .= array_pop($crates[$key]);
    }
    return $output;
}

function getCrates(array $lines): array
{
    $numberOfCrates = getNumberOfCrates($lines);
    $crates = [];
    foreach ($lines as $line) {
        if (!str_contains($line, '[')) {
            continue;
        }

        for ($col = 1; $col <= $numberOfCrates; $col++) {
            $charPos = (4 * ($col-1)) + 1;
            $crate = substr($line, $charPos, 1);
            if (preg_match('/[A-Z]/', $crate)) {
                $crates[$col][] = $crate;
            }
        }
    }

    foreach($crates as $index => $stack) {
        krsort($stack);
        $crates[$index] = array_values($stack);
    }

    return $crates;
}

function getNumberOfCrates(array $lines): int
{
    foreach ($lines as $line) {
        if (str_contains($line, '1')) {
            $crates = explode(' ', $line);
            return (int) max($crates);
        }
    }

    return 0;
}

function moveCrates9000(array &$crates, int $numberToMove, int $source, int $target): void
{
    for ($i = 0; $i < $numberToMove; $i++) {
        $item = array_pop($crates[$source]);
        $crates[$target][] = $item;
    }
}

function moveCrates9001(array &$crates, int $numberToMove, int $source, int $target): void
{
    for ($i = 0; $i < $numberToMove; $i++) {
        $movedCrates[] = array_pop($crates[$source]);
    }
    krsort($movedCrates);

    $crates[$target] = array_merge($crates[$target], array_values($movedCrates));
}
