<?php

include('common.php');
$input = getInput();

print "Part 1: " . getSandUnits($input, false) . "\n";
print "Part 2: " . getSandUnits($input, true) . "\n";

function getSandUnits(array $input, bool $withFloor): int
{
    $sandOriginRow = 0;
    $sandOriginCol = 500;
    $map = buildMap($input);
    $abyssRow = max(array_keys($map));
    $sandUnits = 0;
    try {
        while (true) {
            addSandUnit($map, $sandOriginRow, $sandOriginCol, $abyssRow, $withFloor);
            $sandUnits++;
        }
    } catch (Exception) {
        return $sandUnits;
    }
}

function buildMap(array $instructions): array
{
    $map = [];
    foreach ($instructions as $instruction) {
        $steps = explode(' -> ', $instruction);
        for ($i = 0; $i < count($steps) - 1; $i++) {
            list ($startCol, $startRow) = explode(',', $steps[$i]);
            list ($endCol, $endRow) = explode(',', $steps[$i+1]);
            addMapStep($map, $startRow, $startCol, $endRow, $endCol);
        }
    }

    // pad empty rows
    $maxRow = max(array_keys($map));
    for ($row = 0; $row <= $maxRow; $row++) {
        if (!array_key_exists($row, $map)) {
            $map[$row] = [];
        }
    }

    // Add another row below the bottom
    $map[$maxRow+1] = [];

    return $map;
}

function addMapStep(array &$map, int $startRow, int $startCol, int $endRow, int $endCol): void
{
    if ($endRow < $startRow) {
        $t = $endRow;
        $endRow = $startRow;
        $startRow = $t;
    }

    if ($endCol < $startCol) {
        $t = $endCol;
        $endCol = $startCol;
        $startCol = $t;
    }

    for ($row = $startRow; $row <= $endRow; $row++) {
        for ($col = $startCol; $col <= $endCol; $col++) {
            $map[$row][$col] = '#';
        }
    }
}

/**
 * @throws Exception
 */
function addSandUnit(array &$map, int $startRow, int $sandCol, int $abyssRow, bool $withFloor): void
{
    for ($sandRow = $startRow; $sandRow < $abyssRow; $sandRow++) {
        if (!array_key_exists($sandCol, $map[$sandRow+1])) {
            // we can fall straight down
            continue;
        } elseif (!array_key_exists($sandCol-1, $map[$sandRow+1])) {
            // we can fall down and to the left
            $sandCol--;
        } elseif (!array_key_exists($sandCol+1, $map[$sandRow+1])) {
            // we can fall down and to the right
            $sandCol++;
        } elseif (!array_key_exists($sandCol, $map[$sandRow])) {
            // we can't fall down at all - place our sand here, and stop
            $map[$sandRow][$sandCol] = 'o';
            return;
        } else {
            // we can't fall down, and there's already sand here -  we have a problem
            throw new Exception('Sand flow blocked');
        }
    }

    if ($withFloor === false) {
        throw new Exception('Reached abyss');
    }

    $map[$sandRow][$sandCol] = 'o';
}
