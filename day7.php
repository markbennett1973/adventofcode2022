<?php

include('common.php');

const MAX_SPACE_USED = 40000000;

$input = getInput();
print "Part 1: " . getLargeDirectories($input) . "\n";
print "Part 2: " . getSpaceToFree($input) . "\n";

function getLargeDirectories(array $input): int
{
    $tree = buildTree($input);
    $totalSize = 0;
    getLargeDirectoriesTotal($tree, $totalSize);
    return $totalSize;
}

function getSpaceToFree(array $input): int
{
    $tree = buildTree($input);
    $totalSpaceUsed = $tree->getTotalSize();
    $spaceToFree = $totalSpaceUsed - MAX_SPACE_USED;
    $deletionCandidates = [];
    findDeletionCandidates($tree, $spaceToFree, $deletionCandidates);
    return min($deletionCandidates);
}

function getLargeDirectoriesTotal(Dir $dir, &$totalSize): void
{
    foreach ($dir->dirs as $subDir) {
        $subDirSize = $subDir->getTotalSize();
        if ($subDirSize <= 100000) {
            $totalSize += $subDirSize;
        }

        getLargeDirectoriesTotal($subDir, $totalSize);
    }
}

function findDeletionCandidates(Dir $dir, int $spaceToFree, array &$deletionCandidates): void
{
    foreach ($dir->dirs as $subDir) {
        $subDirSize = $subDir->getTotalSize();
        if ($subDirSize >= $spaceToFree) {
            $deletionCandidates[] = $subDirSize;
        }

        findDeletionCandidates($subDir, $spaceToFree, $deletionCandidates);
    }
}

function buildTree(array $lines): Dir
{
    $root = new Dir();
    $current = $root;

    foreach ($lines as $line) {
        switch (true) {
            case $line === '$ cd /':
                $current = $root;
                break;

            case $line === '$ ls':
                break;

            case $line ==='$ cd ..':
                $current = $current->parent;
                break;

            case str_starts_with($line, '$ cd'):
                $newDirName = substr($line, 5);
                $current = $current->dirs[$newDirName];
                break;

            case str_starts_with($line, 'dir'):
                $current->addDir(substr($line, 4));
                break;

            default:
                list($size, $filename) = explode(' ', $line);
                $current->addFile($filename, (int) $size);
        }
    }

    return $root;
}

class Dir
{
    public ?Dir $parent = null;
    /** @var Dir[] */
    public array $dirs = [];
    public array $files = [];

    public function addDir(string $dirName): void
    {
        $dir = new Dir();
        $dir->parent = $this;
        $this->dirs[$dirName] = $dir;
    }

    public function addFile(string $filename, int $size): void
    {
        $this->files[$filename] = $size;
    }

    public function getTotalSize(): int
    {
        $totalSize = 0;
        foreach ($this->files as $size) {
            $totalSize += $size;
        }

        foreach ($this->dirs as $dir) {
            $totalSize += $dir->getTotalSize();
        }

        return $totalSize;
    }
}