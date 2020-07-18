<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

interface ComponentInterface
{
    /**
     * @param VisitorInterface $visitor
     * @return void
     */
    public function render(VisitorInterface $visitor): void;
}