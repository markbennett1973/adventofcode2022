<?php

include('common.php');
$input = getInput();

print "Part 1: " . getSignalStrength($input) . "\n";
print "Part 2:\n" . drawLetters($input) . "\n";

function getSignalStrength(array $instructions): int
{
    $cycle = 0;
    $x = 1;
    $signalStrength = 0;

    foreach ($instructions as $instruction) {
        $cycle++;
        if (str_starts_with($instruction, 'addx')) {
            list($op, $value) = explode(' ', $instruction);
            checkIfInteresting($cycle, $x, $signalStrength);
            $cycle++;
            checkIfInteresting($cycle, $x, $signalStrength);
            $x += (int) $value;
        } else {
            checkIfInteresting($cycle, $x, $signalStrength);
        }
    }

    return $signalStrength;
}

function checkIfInteresting(int $cycle, int $x, int &$signalStrength): void
{
    if (($cycle - 20) % 40 === 0) {
        $signalStrength += ($cycle * $x);
    }
}

function drawLetters(array $instructions): string
{
    $cycle = 0;
    $x = 1;
    $crt = [];

    foreach ($instructions as $instruction) {
        $cycle++;
        if (str_starts_with($instruction, 'addx')) {
            list($op, $value) = explode(' ', $instruction);
            checkCrtState($cycle, $x, $crt);
            $cycle++;
            checkCrtState($cycle, $x, $crt);
            $x += (int) $value;
        } else {
            checkCrtState($cycle, $x, $crt);
        }
    }

    return drawCrt($crt);
}

function checkCrtState(int $cycle, int $x, array &$crt): void
{
    $char = ' ';

    $position = ($cycle % 40) - 1;
    if ($x >= $position - 1 && $x <= $position + 1) {
        $char = '#';
    }

    $crt[$cycle-1] = $char;
}

function drawCrt(array $crt): string
{
    $output = '';
    foreach ($crt as $index => $character) {
        $output .= $character;
        if (($index + 1) % 40 === 0) {
            $output .= "\n";
        }
    }

    return $output;
}