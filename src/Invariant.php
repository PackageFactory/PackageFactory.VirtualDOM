<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

final class Invariant
{
    /**
     * @param boolean $condition
     * @param string $message
     * @return void
     * @throws InvariantException
     */
    public static function check(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new InvariantException($message);
        }
    }
}