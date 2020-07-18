<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Model;

interface VisitorInterface
{
    public function onElement(Element $element): void;
    public function onFragment(Fragment $fragment): void;
    public function onText(Text $text): void;
}