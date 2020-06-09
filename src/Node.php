<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

abstract class Node
{
    abstract public function __toString(): string;
}