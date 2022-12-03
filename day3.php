<?php

include('common.php');
$input = getInput();
print "Part 1: " . getPriorities($input) . "\n";
print "Part 2: " . getBadges($input) . "\n";

function getPriorities(array $input): int
{
    $total = 0;
    foreach($input as $line) {
        $commonItem = getCommonItem($line);
        $total += getPriority($commonItem);
    }

    return $total;
}

function getCommonItem(string $line): string
{
    $length = strlen($line)/2;
    $line = str_split($line);
    $a = array_slice($line, 0, $length);
    $b = array_slice($line, $length);

    $common = array_intersect($a, $b);
    return reset($common);
}

function getPriority(string $item): int
{
    $code = ord($item);
    if ($code > 96) {
        return $code - 96;
    }

    return $code - 64 + 26;
}

function getBadges(array $lines): int
{
    $total = 0;
    $rucksacks = count($lines) / 3;
    for ($i = 0; $i < $rucksacks; $i++) {
        $badge = getBadge(
            $lines[$i * 3],
            $lines[$i * 3 + 1],
            $lines[$i * 3 + 2]
        );
        $total += getPriority($badge);
    }

    return $total;
}

function getBadge($a, $b, $c)
{
    $common = array_intersect(str_split($a), str_split($b), str_split($c));
    return reset($common);
}