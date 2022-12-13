<?php

include('common.php');
$input = getInput(false);

print "Part 1: " . getMonkeyBusiness($input, 20, 3) . "\n";
print "Part 2: " . getMonkeyBusiness($input, 10000, 1) . "\n";

function getMonkeyBusiness(array $input, int $rounds, int $worryDivisor): int
{
    $monkeys = getMonkeys($input, $worryDivisor);
    for ($round = 1; $round <= $rounds; $round++) {
        foreach ($monkeys as $monkey) {
            $monkey->inspectItems($monkeys);
        }
    }

    $monkeyBusiness = array_map(
        fn(Monkey $monkey) => $monkey->totalInspections,
        $monkeys
    );

    rsort($monkeyBusiness);
    return $monkeyBusiness[0] * $monkeyBusiness[1];
}

/**
 * @return array|Monkey[]
 */
function getMonkeys($input, int $worryDivisor): array
{
    $monkeys = [];
    $monkeysCount = ceil(count($input)/7);
    for ($i = 0; $i < $monkeysCount; $i++) {
        $monkeyData = array_slice($input, $i * 7, 7);
        $monkeys[$i] = new Monkey($monkeyData, $worryDivisor);
    }

    return $monkeys;
}

class Monkey
{
    public array $items;
    private int $divisor;
    private int $trueMonkeyTarget;
    private int $falseMonkeyTarget;
    private string $operation;
    private string $operationValue;
    public int $totalInspections;
    private int $worryDivisor;

    public function __construct(array $monkeyData, int $worryDivisor)
    {
        $items = str_replace('  Starting items: ', '', $monkeyData[1]);
        $this->items = array_map('intval', explode(', ', $items));
        $operationString = str_replace('  Operation: new = old ', '', $monkeyData[2]);
        list ($op, $opValue) = explode(' ', $operationString);
        $this->operation = $op;
        $this->operationValue = $opValue;
        $this->divisor = str_replace('  Test: divisible by ', '', $monkeyData[3]);
        $this->trueMonkeyTarget = str_replace('    If true: throw to monkey ', '', $monkeyData[4]);
        $this->falseMonkeyTarget = str_replace('    If false: throw to monkey ', '', $monkeyData[5]);
        $this->totalInspections = 0;
        $this->worryDivisor = $worryDivisor;
    }

    /**
     * @param array|Monkey[] $monkeys
     */
    public function inspectItems(array $monkeys): void
    {
        foreach ($this->items as $index => $worryLevel) {
            $newWorryLevel = $this->getNewWorryLevel($worryLevel);
            $itemTarget = $this->getItemTarget($newWorryLevel);
            unset($this->items[$index]);
            $monkeys[$itemTarget]->items[] = $newWorryLevel;
            $this->totalInspections++;
        }
    }

    private function getNewWorryLevel(int $oldWorryLevel): int
    {
        if ($this->operationValue === 'old') {
            $opValue = $oldWorryLevel;
        } else {
            $opValue = (int) $this->operationValue;
        }

        if ($this->operation === '+') {
            $newLevel = $oldWorryLevel + $opValue;
        } else {
            $newLevel = $oldWorryLevel * $opValue;
        }

        if (is_float($newLevel)) {
            $x = 1;
        }

        return floor($newLevel / $this->worryDivisor);
    }

    private function getItemTarget(int $worryLevel): int
    {
        if ($worryLevel % $this->divisor === 0) {
            return $this->trueMonkeyTarget;
        } else {
            return $this->falseMonkeyTarget;
        }
    }
}