<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

interface ComponentInterface
{
    /**
     * @return Node
     */
    public function render(): Node;
}