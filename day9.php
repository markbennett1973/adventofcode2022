<?php

include('common.php');
$input = getInput();
print "Part 1: " . moveRope($input, 2) . "\n";
print "Part 2: " . moveRope($input, 10) . "\n";

function moveRope(array $instructions, int $length): int
{
    for ($i = 0; $i < $length; $i++) {
        $rope[$i] = ['x' => 0, 'y' => 0];
    }

    $visited = [];
    foreach ($instructions as $instruction) {
        list ($direction, $distance) = explode(' ', $instruction);
        for ($i = 0; $i < $distance; $i++) {
            foreach ($rope as $index => $link) {
                if ($index === 0) {
                    moveHead($rope, $direction);
                } else {
                    moveTail($rope, $index);
                }
            }

            // print "$instruction -> Head ".$rope[0]['x'].",".$rope[0]['y']." Tail ".$rope[1]['x'].','.$rope[1]['y']."\n";

            $visitedKey = $rope[$length-1]['x'].','.$rope[$length-1]['y'];
            $visited[$visitedKey] = true;
        }
    }

    return count($visited);
}

function moveHead(array &$rope, string $direction): void
{
    switch ($direction) {
        case 'L':
            $rope[0]['x']--;
            break;

        case 'R':
            $rope[0]['x']++;
            break;

        case 'U':
            $rope[0]['y']++;
            break;

        case 'D':
            $rope[0]['y']--;
            break;
    }
}

function moveTail(array &$rope, int $index): void
{
    $headX = $rope[$index-1]['x'];
    $headY = $rope[$index-1]['y'];
    $tailX = $rope[$index]['x'];
    $tailY = $rope[$index]['y'];

    $dX = abs($headX - $tailX);
    $dY = abs($headY - $tailY);
    if ($dX <= 1 && $dY <= 1) {
        // No need to do anything
        return;
    }

    if ($headX > $tailX) {
        $rope[$index]['x']++;
    } elseif ($headX < $tailX) {
        $rope[$index]['x']--;
    }

    if ($headY > $tailY) {
        $rope[$index]['y']++;
    } elseif ($headY < $tailY) {
        $rope[$index]['y']--;
    }
}
